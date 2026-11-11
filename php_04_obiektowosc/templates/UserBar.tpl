<div class="user-bar">
    <span>Zalogowano jako: {$userName|escape:'html'}</span>
    <form action="{$appRoot|escape:'html'}/index.php" method="post">
        <input type="hidden" name="action" value="logout">
        <input type="hidden" name="csrf" value="{$csrfToken|escape:'html'}">
        <button type="submit">Wyloguj</button>
    </form>
</div>
