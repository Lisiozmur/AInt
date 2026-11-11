<?php
namespace App\Core;

class Auth {
    public static function start(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
            session_start();
        }
        if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    public static function loggedIn(): bool {
        self::start();
        return !empty($_SESSION['zalogowany']);
    }
    public static function token(): string {
        self::start();
        return $_SESSION['csrf'];
    }
    public static function validToken(mixed $token): bool {
        return is_string($token) && hash_equals(self::token(), $token);
    }
    public static function login(string $login): void {
        self::start();
        session_regenerate_id(true);
        $_SESSION['zalogowany'] = true;
        $_SESSION['login'] = $login;
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    public static function logout(): void {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
