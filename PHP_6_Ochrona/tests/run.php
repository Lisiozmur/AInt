<?php

declare(strict_types=1);

// Testy logiki biznesowej, ról i routingu bez Composera i bez bazy danych.
// Zaślepka Smarty nie weryfikuje renderowania HTML przez prawdziwy silnik.
require __DIR__ . '/stubs/Smarty.php';
require __DIR__ . '/../core/ClassLoader.php';
(new Core\ClassLoader(['Core\\' => __DIR__ . '/../core/', 'App\\' => __DIR__ . '/../app/']))->register();
require __DIR__ . '/../core/functions.php';

ob_start();
session_start();
$_SESSION = ['csrf_token' => bin2hex(random_bytes(32))];
$_SERVER['REQUEST_METHOD'] = 'GET';

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('BŁĄD: ' . $message);
    }
    echo 'OK: ' . $message . PHP_EOL;
}

check(class_exists(App\Models\LoanCalculator::class), 'ClassLoader ładuje model kredytu');
check(class_exists(Core\Router::class), 'ClassLoader ładuje router Core');
check(class_exists(App\Controllers\LoginCtrl::class), 'ClassLoader ładuje obiektowy LoginCtrl');
check(app_url('loanForm') === 'index.php?action=loanForm', 'link do punktu wejścia');
check(app_url('login', ['next' => 'loanForm']) === 'index.php?action=login&next=loanForm', 'link z parametrem next');
check(app_url('home', ['action' => 'admin']) === 'index.php?action=home', 'parametry nie nadpisują action');
$_GET = ['action' => ['admin'], 'next' => 'loanForm'];
check(get_string('action', 'home') === 'home', 'odrzucenie tablicy w GET');
check(param_string('next') === 'loanForm', 'funkcja pobrania parametru GET');
$_POST = ['login' => 'user@example.test', 'csrf_token' => csrf_token()];
check(post_string('login') === 'user@example.test', 'funkcja pobrania parametru POST');
check(csrf_valid(), 'poprawny token CSRF');
$_POST['csrf_token'] = 'niewlasciwy';
check(!csrf_valid(), 'odrzucenie błędnego CSRF');
$_POST['csrf_token'] = csrf_token();
check(decimal_number('7,5') === 7.5, 'dziesiętne zapisane przecinkiem');
check(decimal_number('x123') === null, 'odrzucenie niepoprawnej liczby');
$zero = App\Models\LoanCalculator::calculate(1200, 0, 1);
check($zero['months'] === 12 && $zero['monthly'] === 100.0, 'obliczenia kredytu 0%');
$example = App\Models\LoanCalculator::calculate(100000, 12, 1);
check(abs($example['monthly'] - 8884.88) < 0.02, 'miesięczna rata kredytu');

$repo = new App\Models\DemoUserRepository();
$user = $repo->verify('user@example.test', 'Kredyt123!');
$admin = $repo->verify('admin@example.test', 'Admin123!');
check($user === ['login' => 'user@example.test', 'role' => 'user'], 'weryfikacja konta user hashem');
check($admin === ['login' => 'admin@example.test', 'role' => 'admin'], 'weryfikacja konta admin hashem');
check($repo->verify('admin@example.test', 'zle_haslo') === null, 'złe hasło jest odrzucane');
check($repo->verify('nie_ma@example.test', 'Kredyt123!') === null, 'nieznane konto jest odrzucane');
check(!is_logged_in() && !has_role('admin'), 'gość nie ma roli');

$oldToken = csrf_token();
set_user_role($user['login'], $user['role']);
check(is_logged_in() && current_role() === 'user' && current_user() === 'user@example.test', 'logowanie zapisuje sesję');
check(csrf_token() !== $oldToken, 'logowanie regeneruje token CSRF');
check(has_role('user') && has_role('user', 'admin') && !has_role('admin'), 'sprawdzanie ról');

$fakeSmarty = new Smarty\Smarty();
$app = new Core\AppContext(new Core\Config('Test', 'zł'), new Core\Messages(), $fakeSmarty);
$router = new Core\Router($app);
$router->add('home', App\Controllers\HomeCtrl::class, 'index', ['GET']);
$router->add('loanForm', App\Controllers\LoanCtrl::class, 'form', ['GET'], ['user', 'admin']);
$router->add('loanCalc', App\Controllers\LoanCtrl::class, 'calculate', ['POST'], ['user', 'admin']);
$router->add('admin', App\Controllers\AdminCtrl::class, 'index', ['GET'], ['admin']);
$router->dispatch('home');
check($fakeSmarty->displayedTemplate === 'home.tpl', 'router uruchamia kontroler publiczny');
$router->dispatch('loanForm');
check($fakeSmarty->displayedTemplate === 'loan/form.tpl', 'rola user otwiera kalkulator');
$router->dispatch('admin');
check(http_response_code() === 403 && $fakeSmarty->displayedTemplate === 'error.tpl', 'rola user nie otwiera panelu admin (403)');
$router->dispatch('nieIstnieje');
check(http_response_code() === 404, 'nieznana akcja zwraca 404');
$_SERVER['REQUEST_METHOD'] = 'POST';
$router->dispatch('loanForm');
check(http_response_code() === 405, 'błędna metoda HTTP zwraca 405');
$_SERVER['REQUEST_METHOD'] = 'GET';

set_user_role($admin['login'], $admin['role']);
check(has_role('admin') && !has_role('user'), 'rola admin poprawnie zastępuje rolę user');
$router->dispatch('admin');
check($fakeSmarty->displayedTemplate === 'admin/index.tpl', 'router wpuszcza administratora');
$token = csrf_token();
clear_user_role();
check(!is_logged_in() && current_role() === null, 'wylogowanie usuwa rolę');
check(csrf_token() !== $token, 'wylogowanie regeneruje token CSRF');

ob_end_flush();
echo 'WSZYSTKIE TESTY ZAKOŃCZONE POMYŚLNIE' . PHP_EOL;
