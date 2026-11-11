{include file='Header.tpl' pageTitle='Kalkulator kredytowy'}
<main>
    <h1>Kalkulator kredytowy</h1>
    {include file='UserBar.tpl'}
    <form action="{$appRoot|escape:'html'}/index.php?action=doCalc" method="post">
        <input type="hidden" name="csrf" value="{$csrfToken|escape:'html'}">
        <div class="field"><label for="kwota">Kwota kredytu (zł):</label><input id="kwota" name="kwota" type="text" inputmode="decimal" required value="{$form->kwota|escape:'html'}"></div>
        <div class="field"><label for="lata">Liczba lat:</label><input id="lata" name="lata" type="number" min="1" max="100" step="1" required value="{$form->lata|escape:'html'}"></div>
        <div class="field"><label for="oprocentowanie">Oprocentowanie roczne (%):</label><input id="oprocentowanie" name="oprocentowanie" type="text" inputmode="decimal" required value="{$form->oprocentowanie|escape:'html'}"></div>
        <button type="submit">Oblicz ratę</button>
    </form>
    {if $errors}<ol class="error">{foreach $errors as $error}<li>{$error|escape:'html'}</li>{/foreach}</ol>{/if}
    {if $hasResult}<div class="result">Miesięczna rata:<br><strong>{$result->rataTekst|escape:'html'} zł</strong></div>{/if}
</main>
{include file='Footer.tpl'}
