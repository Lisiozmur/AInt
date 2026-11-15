<?php

declare(strict_types=1);

use App\Controllers\HomeCtrl;
use App\Controllers\LoanCtrl;

// JEDYNY publiczny skrypt PHP aplikacji; cała nawigacja przechodzi przez parametr action.
$app = require dirname(__DIR__) . '/init.php';
$action = $_GET['action'] ?? 'home';
if (!is_string($action)) {
    $action = '';
}

try {
    // Punkt 5: jawna lista dozwolonych akcji, nie dowolnych nazw klas/metod.
    switch ($action) {
        case 'home':
            (new HomeCtrl($app))->index();
            break;
        case 'loanForm':
            (new LoanCtrl($app))->form();
            break;
        case 'loanCalc':
            (new LoanCtrl($app))->calculate();
            break;
        default:
            http_response_code(404);
            $app->messages->addError('Nie znaleziono żądanej strony.');
            $app->render('error.tpl', ['page_title' => 'Strona nie istnieje']);
    }
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    $app->messages->addError('Wystąpił błąd serwera. Spróbuj ponownie później.');
    $app->render('error.tpl', ['page_title' => 'Błąd aplikacji']);
}
