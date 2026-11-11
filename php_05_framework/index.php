<?php
// JEDYNY PUNKT WEJSCIA: akcja wybierana parametrem action.
require_once __DIR__ . '/init.php';

use App\Controllers\CalcCtrl;
use App\Controllers\HomeCtrl;
use App\Controllers\LoginCtrl;
use App\Core\Auth;

$action = $_GET['action'] ?? 'home';
if (!is_string($action)) {
    http_response_code(400);
    exit('Bledna akcja.');
}
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
// Lista dozwolonych akcji; nie wykonujemy nazw przekazanych przez uzytkownika.
$routes = [
    'login'  => ['GET',  LoginCtrl::class, 'show'],
    'doLogin' => ['POST', LoginCtrl::class, 'login'],
    'logout' => ['POST', LoginCtrl::class, 'logout'],
    'home'   => ['GET',  HomeCtrl::class,  'show'],
    'calc'   => ['GET',  CalcCtrl::class,  'show'],
    'doCalc' => ['POST', CalcCtrl::class,  'calculate'],
];
if (!isset($routes[$action])) {
    http_response_code(404);
    exit('Nieznana akcja.');
}
[$allowedMethod, $class, $handler] = $routes[$action];
if ($method !== $allowedMethod) {
    header('Allow: ' . $allowedMethod);
    http_response_code(405);
    exit('Niedozwolona metoda.');
}
// CENTRALNA OCHRONA kazdej akcji oprocz logowania.
if (!in_array($action, ['login', 'doLogin'], true) && !Auth::loggedIn()) {
    redirectTo('login');
}
$ctrl = new $class();
$ctrl->$handler();
