<?php
// Wspólna ochrona stron aplikacji.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(array('httponly' => true, 'samesite' => 'Lax'));
    session_start();
}

function require_login() {
    if (empty($_SESSION['zalogowany'])) {
        header('Location: '._APP_ROOT.'/app/login.php');
        exit();
    }
}
