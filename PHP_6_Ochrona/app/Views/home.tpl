{include file='layout/header.tpl'}
<section class="hero">
  <div class="eyebrow">PROJEKT PHP · ZADANIA 5, 5A, 5B, 6, 6A, 6B</div>
  <h1>Sprawdź wysokość swojej <em>raty kredytu.</em></h1>
  <p>Przykładowa aplikacja z bezpiecznym punktem wejścia, obiektowym routerem, logowaniem, kontrolą ról i szablonami Smarty.</p>
  {if $is_logged_in}
    <a class="button button--primary" href="{$routes.loanForm|escape:'html'}">Otwórz kalkulator <span aria-hidden="true">→</span></a>
  {else}
    <a class="button button--primary" href="{$routes.login|escape:'html'}">Zaloguj się, aby obliczyć ratę <span aria-hidden="true">→</span></a>
  {/if}
  <div class="hero-stats">
    <div><strong>1</strong><span>punkt wejścia</span></div>
    <div><strong>2</strong><span>role użytkowników</span></div>
    <div><strong>PHP</strong><span>+ Smarty</span></div>
  </div>
</section>
<section class="section">
  <h2>Co zawiera aplikacja?</h2>
  <div class="cards">
    <article class="card"><div class="card-icon">01</div><h3>Router obiektowy</h3><p>Parametr <code>action</code> wskazuje zatwierdzoną akcję. Router sprawdza metodę HTTP i uprawnienia do strony.</p></article>
    <article class="card"><div class="card-icon">02</div><h3>Logowanie i sesja</h3><p>Kontroler <code>LoginCtrl</code> sprawdza hasło, a po zalogowaniu przechowuje rolę w sesji PHP.</p></article>
    <article class="card"><div class="card-icon">03</div><h3>Kontrola ról</h3><p>Kalkulator dostępny jest dla użytkownika i administratora. Panel administracyjny tylko dla roli <code>admin</code>.</p></article>
  </div>
</section>
{include file='layout/footer.tpl'}
