<?php
// WIDOK logowania (dostęp przez kontroler login.php).
require_once dirname(__FILE__).'/../config.php';
require_once _ROOT_PATH.'/auth.php';
if (basename($_SERVER['SCRIPT_FILENAME']) === 'login_view.php') {
    header('Location: '._APP_ROOT.'/app/login.php');
    exit();
}
$login = isset($login) ? $login : '';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Logowanie</title>
</head>
<body>
<h2>Logowanie</h2>
<form action="<?php echo _APP_ROOT; ?>/app/login.php" method="post">
    <label for="login">Login: </label>
    <input id="login" type="text" name="login" required value="<?php echo htmlspecialchars($login, ENT_QUOTES, 'UTF-8'); ?>" /><br />
    <label for="haslo">Hasło: </label>
    <input id="haslo" type="password" name="haslo" required /><br />
    <input type="submit" value="Zaloguj" />
</form>
<?php if (!empty($messages)) { ?>
    <ol style="margin: 20px; padding: 10px 10px 10px 30px; background-color: #f88; width: 300px;">
        <?php foreach ($messages as $msg) { ?>
            <li><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
    </ol>
<?php } ?>
</body>
</html>
