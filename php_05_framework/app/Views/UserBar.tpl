<nav class="user-bar" aria-label="Menu">
    <div class="links"><a href="{$appRoot|escape:'html'}/index.php?action=home">Strona główna</a>
    <a href="{$appRoot|escape:'html'}/index.php?action=calc">Kalkulator kredytowy</a></div>
    <span>Zalogowano jako: {$userName|escape:'html'}</span>
    <form action="{$appRoot|escape:'html'}/index.php?action=logout" method="post">
        <input type="hidden" name="csrf" value="{$csrfToken|escape:'html'}">
        <button type="submit">Wyloguj</button>
    </form>
</nav>
