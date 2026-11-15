{include file='layout/header.tpl'}
<section class="page-intro">
  <span class="eyebrow">WYNIK SYMULACJI</span>
  <h1>Oto Twoje wyliczenie.</h1>
  <p>Szacunkowe warunki spłaty dla wskazanych parametrów.</p>
</section>
<section class="result-highlight">
  <span>Szacunkowa rata miesięczna</span>
  <strong>{$monthly|escape:'html'}</strong>
  <p>Raty równe przez {$months|escape:'html'} miesięcy</p>
</section>
<div class="result-grid">
  <div class="result-tile"><span>Całkowita suma spłat</span><strong>{$total|escape:'html'}</strong></div>
  <div class="result-tile"><span>Łączne odsetki</span><strong>{$interest|escape:'html'}</strong></div>
  <div class="result-tile"><span>Kwota kredytu</span><strong>{$amount|escape:'html'}</strong></div>
  <div class="result-tile"><span>Oprocentowanie / okres</span><strong>{$rate|escape:'html'} / {$years|escape:'html'} lat</strong></div>
</div>
<div class="result-actions"><a class="button button--primary" href="{$routes.loanForm|escape:'html'}">Oblicz ponownie <span aria-hidden="true">→</span></a><a class="button button--secondary" href="{$routes.home|escape:'html'}">Wróć na stronę główną</a></div>
<p class="fine-print">Wartości mają charakter orientacyjny. Końcowa suma rat może różnić się o grosze w zależności od zasad zaokrąglania i harmonogramu banku.</p>
{include file='layout/footer.tpl'}
