<?php

declare(strict_types=1);

use Core\AppContext;
use Core\ClassLoader;
use Core\Config;
use Core\Messages;
use Smarty\Smarty;

// Każde żądanie aplikacji przechodzi przez public/index.php -> init.php.
$root = __DIR__;
$vendorAutoload = $root . '/vendor/autoload.php';

if (!is_file($vendorAutoload)) {
    http_response_code(503);
    header('Content-Type: text/plain; charset=utf-8');
    exit("Brakuje biblioteki Smarty. W katalogu projektu uruchom: composer install\n");
}

// Composer obsługuje bibliotekę Smarty, własny ClassLoader klasy naszego projektu.
require_once $vendorAutoload;
require_once $root . '/core/ClassLoader.php';

$loader = new ClassLoader([
    'Core\\' => $root . '/core/',
    'App\\' => $root . '/app/',
]);
$loader->register();

require_once $root . '/core/functions.php';

$settings = require $root . '/config.php';
$config = new Config($settings['app_name'], $settings['currency']);
$messages = new Messages();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'use_strict_mode' => true,
    ]);
}
if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$compileDir = $root . '/var/smarty/compile';
$cacheDir = $root . '/var/smarty/cache';
foreach ([$compileDir, $cacheDir] as $directory) {
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        throw new RuntimeException('Nie można utworzyć katalogu: ' . $directory);
    }
}

$smarty = new Smarty();
$smarty->setTemplateDir($root . '/app/Views');
$smarty->setCompileDir($compileDir);
$smarty->setCacheDir($cacheDir);
$smarty->assign('app_name', $config->appName);
$smarty->assign('routes', [
    'home' => app_url('home'),
    'loanForm' => app_url('loanForm'),
    'loanCalc' => app_url('loanCalc'),
]);

return new AppContext($config, $messages, $smarty);
