{include file='layout/header.tpl'}
<section class="hero">
  <div class="eyebrow">PROJEKT PHP · ZADANIA 5, 5A, 5B</div>
  <h1>Sprawdź wysokość swojej <em>raty kredytu.</em></h1>
  <p>Prosty kalkulator kredytowy zbudowany w PHP z kontrolerem głównym, szablonami Smarty oraz automatycznym ładowaniem klas.</p>
  <a class="button button--primary" href="{$routes.loanForm|escape:'html'}">Przejdź do kalkulatora <span aria-hidden="true">→</span></a>
  <div class="hero-stats">
    <div><strong>1</strong><span>punkt wejścia</span></div>
    <div><strong>3</strong><span>akcje w kontrolerze</span></div>
    <div><strong>PHP</strong><span>+ Smarty</span></div>
  </div>
</section>
<section class="section">
  <h2>Co zawiera aplikacja?</h2>
  <div class="cards">
    <article class="card"><div class="card-icon">01</div><h3>Kontroler główny</h3><p>Parametr <code>action</code> wskazuje, którą metodę kontrolera uruchomić. Nie ma osobnych publicznych skryptów dla akcji.</p></article>
    <article class="card"><div class="card-icon">02</div><h3>Rozdzielona struktura</h3><p>Osobne katalogi dla kontrolerów, modelu obliczeń i widoków. Konfiguracja oraz komunikaty są inicjowane centralnie.</p></article>
    <article class="card"><div class="card-icon">03</div><h3>Automatyczne ładowanie</h3><p>Klasa <code>ClassLoader</code> korzysta z <code>spl_autoload_register()</code> i mapuje przestrzenie nazw na katalogi.</p></article>
  </div>
</section>
{include file='layout/footer.tpl'}
