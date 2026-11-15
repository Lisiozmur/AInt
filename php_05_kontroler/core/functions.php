<?php

declare(strict_types=1);

/** Linki prowadzą zawsze do pojedynczego punktu wejścia. */
function app_url(string $action): string
{
    return 'index.php?action=' . rawurlencode($action);
}

/** Bezpiecznie czyta pole formularza. Tablice i inne typy są odrzucane. */
function post_string(string $name): string
{
    $value = $_POST[$name] ?? '';
    return is_string($value) ? trim($value) : '';
}

/** Przyjmuje polski zapis dziesiętny, np. 7,5. */
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

/** Formatuje kwotę dla polskiego użytkownika. */
function format_money(float $amount): string
{
    return number_format($amount, 2, ',', ' ') . ' zł';
}
