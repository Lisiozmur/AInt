<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\DemoUserRepository;
use Core\AppContext;

/** Obiektowy kontroler logowania (zadanie 6) + widok Smarty LoginView.tpl. */
final class LoginCtrl
{
    public function __construct(private AppContext $app)
    {
    }

    public function form(): void
    {
        if (is_logged_in()) {
            redirect_to('loanForm');
        }
        $this->showForm('', get_string('next'));
    }

    public function authenticate(): void
    {
        if (is_logged_in()) {
            redirect_to('loanForm');
        }
        $login = post_string('login');
        $password = post_string('password');
        $next = $this->safeNext(post_string('next'));

        if (!csrf_valid()) {
            http_response_code(403);
            $this->app->messages->addError('Nieprawidłowy token formularza. Odśwież stronę i spróbuj ponownie.');
            $this->showForm($login, $next);
            return;
        }

        // Prosty limit sesyjny do ćwiczeń; w produkcji potrzebny limit także po IP/loginie.
        $lockedUntil = (int) ($_SESSION['login_locked_until'] ?? 0);
        if ($lockedUntil > time()) {
            http_response_code(429);
            $this->app->messages->addError('Zbyt wiele prób logowania. Spróbuj ponownie za kilka minut.');
            $this->showForm($login, $next);
            return;
        }

        $user = (new DemoUserRepository())->verify($login, $password);
        if ($user === null) {
            $attempts = (int) ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['login_attempts'] = $attempts;
            if ($attempts >= 5) {
                $_SESSION['login_locked_until'] = time() + 300;
                $_SESSION['login_attempts'] = 0;
            }
            http_response_code(401);
            $this->app->messages->addError('Nieprawidłowy login lub hasło.');
            $this->showForm($login, $next);
            return;
        }

        unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
        set_user_role($user['login'], $user['role']);
        if ($next === 'admin' && $user['role'] !== 'admin') {
            $next = 'loanForm';
        }
        redirect_to($next !== '' ? $next : 'loanForm');
    }

    public function logout(): void
    {
        if (!csrf_valid()) {
            http_response_code(403);
            $this->app->messages->addError('Nieprawidłowy token wylogowania.');
            $this->app->render('error.tpl', ['page_title' => 'Błąd wylogowania']);
            return;
        }
        clear_user_role();
        redirect_to('home');
    }

    private function showForm(string $login = '', string $next = ''): void
    {
        $this->app->render('auth/LoginView.tpl', [
            'page_title' => 'Logowanie',
            'login_value' => $login,
            'next_action' => $this->safeNext($next),
        ]);
    }

    /** Tylko znane, bezpieczne akcje GET; żadnego dowolnego przekierowania. */
    private function safeNext(string $action): string
    {
        return in_array($action, ['loanForm', 'admin'], true) ? $action : '';
    }
}
