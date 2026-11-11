<?php
require_once dirname(__FILE__).'/config.php';

// Redirect (alternatywa):
// header('Location: '._APP_ROOT.'/app/calc.php');
// exit();

// Forward: kontroler sam sprawdzi, czy użytkownik jest zalogowany.
include _ROOT_PATH.'/app/calc.php';
