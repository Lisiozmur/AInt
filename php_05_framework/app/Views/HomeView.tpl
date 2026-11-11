{include file='Header.tpl' pageTitle='Strona główna'}
<main>
    <h1>Strona główna</h1>
    {include file='UserBar.tpl'}
    <p>Witaj w aplikacji kalkulatora kredytowego.</p>
    <p>Wybierz z menu opcję <a href="{$appRoot|escape:'html'}/index.php?action=calc">Kalkulator kredytowy</a>, aby obliczyć miesięczną ratę.</p>
</main>
{include file='Footer.tpl'}
