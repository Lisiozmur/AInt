<?php
// Konfiguracja projektu XAMPP; brak sztywno wpisanego portu.
define('APP_ROOT', '/php_04_obiektowosc');
define('ROOT_PATH', __DIR__);

// Autoload klas z projektu (model, kontrolery, biblioteka).
spl_autoload_register(function ($class) {
    $dirs = ['/app/model/', '/app/controllers/', '/app/lib/'];
    foreach ($dirs as $dir) {
        $file = ROOT_PATH . $dir . $class . '.class.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

// Uzywamy prawdziwej biblioteki Smarty zainstalowanej przez Composer.
function makeSmarty() {
    $loader = ROOT_PATH . '/vendor/autoload.php';
    if (!is_file($loader)) {
        http_response_code(500);
        exit('Brak biblioteki Smarty. Uruchom w katalogu projektu: composer install (patrz README.txt).');
    }
    require_once $loader;
    $smarty = new \Smarty\Smarty();
    $smarty->setTemplateDir(ROOT_PATH . '/templates');
    $smarty->setCompileDir(ROOT_PATH . '/var/templates_c');
    $smarty->assign('appRoot', APP_ROOT);
    return $smarty;
}
