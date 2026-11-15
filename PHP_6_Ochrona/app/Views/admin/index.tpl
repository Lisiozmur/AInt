{include file='layout/header.tpl'}
<section class="page-intro">
  <span class="eyebrow">TYLKO DLA ROLI ADMIN</span>
  <h1>Panel administratora.</h1>
  <p>Ten zasób jest chroniony przez <code>Core\Router</code>. Sam kontroler <code>AdminCtrl</code> nie zawiera kodu sprawdzającego logowanie.</p>
</section>
<section class="panel admin-panel">
  <span class="eyebrow">DOSTĘP PRZYZNANY</span>
  <h2>Zalogowano jako {$current_user|escape:'html'}</h2>
  <p>Twoja rola w sesji: <strong>{$current_role|escape:'html'}</strong>.</p>
  <p>Spróbuj wylogować się albo zalogować na konto <code>user</code> i otworzyć adres <code>index.php?action=admin</code>. Router odmówi dostępu kodem HTTP 403.</p>
  <a class="button button--primary" href="{$routes.loanForm|escape:'html'}">Przejdź do kalkulatora</a>
</section>
{include file='layout/footer.tpl'}
