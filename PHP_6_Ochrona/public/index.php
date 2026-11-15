<?php

declare(strict_types=1);

use App\Controllers\AdminCtrl;
use App\Controllers\HomeCtrl;
use App\Controllers\LoanCtrl;
use App\Controllers\LoginCtrl;
use Core\Router;

// JEDYNY publiczny punkt wejścia. Definicje trasy i zasady dostępu są tutaj.
$app = require dirname(__DIR__) . '/init.php';

try {
    $router = new Router($app);

    // Zadanie 6b: router zastępuje switch (5) i funkcję dispatch_action (6a).
    // Ostatni argument to lista dopuszczonych ról; [] oznacza zasób publiczny.
    $router->add('home',      HomeCtrl::class,  'index',        ['GET']);
    $router->add('login',     LoginCtrl::class, 'form',         ['GET']);
    $router->add('loginPost', LoginCtrl::class, 'authenticate', ['POST']);
    $router->add('loanForm',  LoanCtrl::class,  'form',         ['GET'],  ['user', 'admin']);
    $router->add('loanCalc',  LoanCtrl::class,  'calculate',    ['POST'], ['user', 'admin']);
    $router->add('admin',     AdminCtrl::class, 'index',        ['GET'],  ['admin']);
    $router->add('logout',    LoginCtrl::class, 'logout',       ['POST'], ['user', 'admin']);

    $router->dispatch(get_string('action', 'home'));
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    $app->messages->addError('Wystąpił błąd serwera. Spróbuj ponownie później.');
    $app->render('error.tpl', ['page_title' => 'Błąd aplikacji']);
}
