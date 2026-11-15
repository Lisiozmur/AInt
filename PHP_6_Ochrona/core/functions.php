<?php

declare(strict_types=1);

use Core\Auth;

/** Budowanie wewnętrznych adresów; action nigdy nie jest nadpisywane parametrami. */
function app_url(string $action, array $params = []): string
{
    unset($params['action']);
    return 'index.php?' . http_build_query(['action' => $action] + $params, '', '&', PHP_QUERY_RFC3986);
}

/** Przekierowanie tylko do akcji tej aplikacji (bez zewnętrznych adresów URL). */
function redirect_to(string $action, array $params = [], int $status = 303): never
{
    if (!in_array($status, [301, 302, 303, 307, 308], true)) {
        throw new InvalidArgumentException('Niepoprawny kod przekierowania.');
    }
    header('Location: ' . app_url($action, $params), true, $status);
    exit;
}

/** Tablice zamiast stringów są odrzucane (m.in. action[]=...). */
function get_string(string $name, string $default = ''): string
{
    $value = $_GET[$name] ?? $default;
    return is_string($value) ? trim($value) : $default;
}

function post_string(string $name, string $default = ''): string
{
    $value = $_POST[$name] ?? $default;
    return is_string($value) ? trim($value) : $default;
}

/** Uniwersalny pomocnik do pobierania parametrów z wybranego źródła. */
function param_string(string $name, string $source = 'GET', string $default = ''): string
{
    return match ($source) {
        'GET' => get_string($name, $default),
        'POST' => post_string($name, $default),
        default => throw new InvalidArgumentException('Źródło parametru musi być GET albo POST.'),
    };
}

/** Token CSRF – obowiązkowy dla formularzy POST modyfikujących stan oraz logowania. */
function csrf_token(): string
{
    return is_string($_SESSION['csrf_token'] ?? null) ? $_SESSION['csrf_token'] : '';
}

function csrf_valid(): bool
{
    $provided = post_string('csrf_token');
    $expected = csrf_token();
    return $provided !== '' && $expected !== '' && hash_equals($expected, $provided);
}

/** Pomocnicze funkcje z zadania 6a; autorytatywny zapis tylko po zweryfikowanym logowaniu. */
function set_user_role(string $login, string $role): void
{
    Auth::logIn($login, $role);
}

function clear_user_role(): void
{
    Auth::logOut();
}

function current_user(): ?string
{
    return Auth::user()['login'] ?? null;
}

function current_role(): ?string
{
    return Auth::user()['role'] ?? null;
}

function is_logged_in(): bool
{
    return Auth::loggedIn();
}

function has_role(string ...$roles): bool
{
    return $roles !== [] && Auth::hasAnyRole($roles);
}

/** Akceptuje polski zapis dziesiętny, np. 7,5. */
function decimal_number(string $value, int $precision = 2): ?float
{
    $normalized = str_replace([',', ' ', "\u{00A0}"], ['.', '', ''], trim($value));
    $regex = '/^\d+(?:\.\d{1,' . $precision . '})?$/D';
    if (!preg_match($regex, $normalized)) {
        return null;
    }
    $result = (float) $normalized;
    return is_finite($result) ? $result : null;
}

function format_money(float $amount): string
{
    return number_format($amount, 2, ',', ' ') . ' zł';
}
