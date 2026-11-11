<?php
// Pasek użytkownika na stronach chronionych.
if (!defined('FROM_VIEW')) { http_response_code(403); exit('Brak dostępu.'); }
?>
<div class="user-bar">
    <span>Zalogowano jako: <?php echo htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8'); ?></span>
    <form action="<?php echo _APP_ROOT; ?>/app/logout.php" method="post">
        <button type="submit">Wyloguj</button>
    </form>
</div>
