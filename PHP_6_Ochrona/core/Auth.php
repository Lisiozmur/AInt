<?php

declare(strict_types=1);

namespace Core;

/** Sesja jest jedynym źródłem tożsamości/roli w trakcie żądania. */
final class Auth
{
    private const SESSION_KEY = 'auth_user';
    private const ALLOWED_ROLES = ['user', 'admin'];

    /** @return array{login:string,role:string}|null */
    public static function user(): ?array
    {
        $stored = $_SESSION[self::SESSION_KEY] ?? null;
        if (!is_array($stored)
            || !isset($stored['login'], $stored['role'])
            || !is_string($stored['login'])
            || !is_string($stored['role'])
            || $stored['login'] === ''
            || !in_array($stored['role'], self::ALLOWED_ROLES, true)) {
            return null;
        }
        return ['login' => $stored['login'], 'role' => $stored['role']];
    }

    public static function loggedIn(): bool
    {
        return self::user() !== null;
    }

    public static function hasAnyRole(array $allowedRoles): bool
    {
        if ($allowedRoles === []) {
            return true;
        }
        $user = self::user();
        return $user !== null && in_array($user['role'], $allowedRoles, true);
    }

    /** Wywoływać tylko po sprawdzeniu hasła po stronie serwera. */
    public static function logIn(string $login, string $role): void
    {
        if ($login === '' || !in_array($role, self::ALLOWED_ROLES, true)) {
            throw new \InvalidArgumentException('Niedozwolona rola lub użytkownik.');
        }
        session_regenerate_id(true); // zapobiega session fixation
        $_SESSION[self::SESSION_KEY] = ['login' => $login, 'role' => $role];
        self::rotateCsrf();
    }

    public static function logOut(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
        self::rotateCsrf();
    }

    public static function rotateCsrf(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
