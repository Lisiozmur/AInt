<?php
class LoginCtrl {
    private $messages;
    private $login = '';
    public function __construct() { $this->messages = new Messages(); }
    public function process() {
        Auth::start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'logout') {
            if (!Auth::validToken($_POST['csrf'] ?? null)) {
                http_response_code(403);
                exit('Niepoprawny token formularza.');
            }
            Auth::logout();
            header('Location: ' . APP_ROOT . '/index.php');
            exit();
        }
        if (Auth::loggedIn()) {
            header('Location: ' . APP_ROOT . '/calc.php');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Auth::validToken($_POST['csrf'] ?? null)) {
                $this->messages->addError('Niepoprawny token formularza.');
            } else {
                $this->login = is_string($_POST['login'] ?? null) ? trim($_POST['login']) : '';
                $password = is_string($_POST['haslo'] ?? null) ? $_POST['haslo'] : '';
                // Konto cwiczeniowe: student / kredyt123
                $hash = '$2y$12$bVoHER1jqwT64zq4zm79UOP0FBB6BAkTPEoBJ93LVTMgDUbUH0G9S';
                if ($this->login === 'student' && password_verify($password, $hash)) {
                    Auth::login($this->login);
                    header('Location: ' . APP_ROOT . '/calc.php');
                    exit();
                }
                $this->messages->addError('Niepoprawny login lub haslo.');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            exit('Metoda niedozwolona.');
        }
        $smarty = makeSmarty();
        $smarty->assign('errors', $this->messages->getErrors());
        $smarty->assign('login', $this->login);
        $smarty->assign('csrfToken', Auth::token());
        $smarty->display('LoginView.tpl');
    }
}
