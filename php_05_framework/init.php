<?php
// Wspolna inicjalizacja aplikacji.
require_once __DIR__ . '/config.php';
require_once ROOT_PATH . '/app/Core/ClassLoader.class.php';
require_once ROOT_PATH . '/functions.php';

$loader = new \App\Core\ClassLoader(ROOT_PATH . '/app');
$loader->register();

\App\Core\Auth::start();
// Obiekty wspolne udostepnia prosty kontener App.
\App\Core\App::init();
