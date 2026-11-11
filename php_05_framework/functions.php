<?php
// Male funkcje pomocnicze rozbudowywane podczas cwiczen.
function appUrl(string $action = ''): string {
    return APP_ROOT . '/index.php' . ($action !== '' ? '?action=' . rawurlencode($action) : '');
}
function redirectTo(string $action): void {
    header('Location: ' . appUrl($action), true, 303);
    exit;
}
function requestText(array $input, string $name): string {
    $value = $input[$name] ?? '';
    return is_string($value) ? trim($value) : '';
}
