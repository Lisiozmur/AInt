<?php
// KONTROLER logowania
require_once dirname(__FILE__).'/../config.php';
require_once _ROOT_PATH.'/auth.php';

if (!empty($_SESSION['zalogowany'])) {
    header('Location: '._APP_ROOT.'/app/calc.php');
    exit();
}

$messages = array();
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) && is_string($_POST['login']) ? trim($_POST['login']) : '';
    $haslo = isset($_POST['haslo']) && is_string($_POST['haslo']) ? $_POST['haslo'] : '';

    // Konto demonstracyjne do zadania; w prawdziwej aplikacji dane są w bazie.
    $poprawny_login = 'student';
    $hash_hasla = '$2y$12$bVoHER1jqwT64zq4zm79UOP0FBB6BAkTPEoBJ93LVTMgDUbUH0G9S';

    if ($login === $poprawny_login && password_verify($haslo, $hash_hasla)) {
        session_regenerate_id(true);
        $_SESSION['zalogowany'] = true;
        $_SESSION['login'] = $login;
        header('Location: '._APP_ROOT.'/app/calc.php');
        exit();
    }
    $messages[] = 'Niepoprawny login lub hasło.';
}

include dirname(__FILE__).'/login_view.php';
