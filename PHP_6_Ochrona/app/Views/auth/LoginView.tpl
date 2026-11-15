{include file='layout/header.tpl'}
<section class="page-intro">
  <span class="eyebrow">ZADANIA 6 · 6A · 6B</span>
  <h1>Zaloguj się.</h1>
  <p>Podaj dane konta demonstracyjnego, aby uzyskać dostęp do chronionych akcji.</p>
</section>
<div class="auth-grid">
  <section class="panel auth-panel">
    <div class="panel-heading"><span class="heading-number">01</span><h2>Logowanie</h2></div>
    <form action="{$routes.loginPost|escape:'html'}" method="post">
      <input type="hidden" name="csrf_token" value="{$csrf_token|escape:'html'}">
      <input type="hidden" name="next" value="{$next_action|escape:'html'}">
      <div class="field">
        <label for="login">Login (e-mail)</label>
        <div class="input-wrap"><input id="login" name="login" type="email" autocomplete="username" maxlength="254" required value="{$login_value|escape:'html'}" placeholder="user@example.test"></div>
      </div>
      <div class="field">
        <label for="password">Hasło</label>
        <div class="input-wrap"><input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Wprowadź hasło"></div>
      </div>
      <button class="button button--primary button--wide" type="submit">Zaloguj <span aria-hidden="true">→</span></button>
    </form>
  </section>
  <aside class="info-panel">
    <span class="eyebrow">DANE TESTOWE</span>
    <h2>Konta demonstracyjne</h2>
    <p>W tym ćwiczeniu dostępne są dwa konta. To przykładowe dane, nie używaj ich w publicznej aplikacji.</p>
    <div class="demo-account"><strong>Użytkownik (user)</strong><code>user@example.test</code><code>Kredyt123!</code></div>
    <div class="demo-account"><strong>Administrator (admin)</strong><code>admin@example.test</code><code>Admin123!</code></div>
    <p class="fine-print">Hasła są weryfikowane za pomocą <code>password_verify()</code>; w repozytorium zapisano tylko ich hashe.</p>
  </aside>
</div>
{include file='layout/footer.tpl'}
