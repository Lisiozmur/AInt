{include file='Header.tpl' pageTitle='Logowanie'}
<main class="login">
    <h1>Logowanie</h1>
    <form action="{$appRoot|escape:'html'}/index.php" method="post">
        <input type="hidden" name="csrf" value="{$csrfToken|escape:'html'}">
        <div class="field">
            <label for="login">Login:</label>
            <input id="login" name="login" type="text" required value="{$login|escape:'html'}">
        </div>
        <div class="field">
            <label for="haslo">Hasło:</label>
            <input id="haslo" name="haslo" type="password" required>
        </div>
        <button type="submit">Zaloguj</button>
    </form>
    {if $errors}
        <ol class="error">
            {foreach $errors as $error}<li>{$error|escape:'html'}</li>{/foreach}
        </ol>
    {/if}
</main>
{include file='Footer.tpl'}
