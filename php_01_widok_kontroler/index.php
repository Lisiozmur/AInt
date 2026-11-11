<?php
require_once dirname(__FILE__).'/config.php';

// Przekierowanie przeglądarki (redirect):
// header('Location: '._APP_URL.'/app/calc.php');
// exit();

// Przekazanie żądania (forward):
include _ROOT_PATH.'/app/calc.php';
