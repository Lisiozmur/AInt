<?php
namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;

class LoginCtrl {
    public function show(): void {
        if (Auth::loggedIn()) redirectTo('home');
        App::render('LoginView.tpl', ['login' => '', 'errors' => []]);
    }
    public function login(): void {
        $messages = App::messages();
        if (!Auth::validToken($_POST['csrf'] ?? null)) {
            http_response_code(403);
            $messages->addError('Niepoprawny token formularza.');
        } else {
            $login = requestText($_POST, 'login');
            $password = $_POST['haslo'] ?? '';
            // Konto demonstracyjne - tylko do cwiczen.
            $hash = '$2y$12$bVoHER1jqwT64zq4zm79UOP0FBB6BAkTPEoBJ93LVTMgDUbUH0G9S';
            if ($login === 'student' && is_string($password) && password_verify($password, $hash)) {
                Auth::login($login);
                redirectTo('home');
            }
            $messages->addError('Niepoprawny login lub haslo.');
        }
        App::render('LoginView.tpl', [
            'login' => requestText($_POST, 'login'),
            'errors' => $messages->getErrors(),
        ]);
    }
    public function logout(): void {
        if (!Auth::validToken($_POST['csrf'] ?? null)) {
            http_response_code(403);
            exit('Niepoprawny token formularza.');
        }
        Auth::logout();
        redirectTo('login');
    }
}
