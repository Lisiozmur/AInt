<?php
require_once dirname(__FILE__).'/../config.php';
require_once _ROOT_PATH.'/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: '._APP_ROOT.'/app/login.php');
    exit();
}

$_SESSION = array();
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
header('Location: '._APP_ROOT.'/app/login.php');
exit();
