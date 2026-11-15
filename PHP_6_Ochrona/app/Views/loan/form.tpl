{include file='layout/header.tpl'}
<section class="page-intro">
  <span class="eyebrow">KALKULATOR KREDYTOWY</span>
  <h1>Oblicz swoją ratę.</h1>
  <p>Wprowadź parametry kredytu. Obliczenia dotyczą rat równych przy stałym oprocentowaniu rocznym.</p>
</section>
<div class="tool-grid">
  <section class="panel">
    <div class="panel-heading"><span class="heading-number">01</span><h2>Parametry kredytu</h2></div>
    <form action="{$routes.loanCalc|escape:'html'}" method="post">
      <input type="hidden" name="csrf_token" value="{$csrf_token|escape:'html'}">
      <div class="field">
        <label for="amount">Kwota kredytu</label>
        <div class="input-wrap"><input id="amount" name="amount" type="text" inputmode="decimal" required value="{$values.amount|escape:'html'}" placeholder="np. 300000"><span class="suffix">PLN</span></div>
        <small>Od 1 000 do 10 000 000 zł</small>
      </div>
      <div class="field">
        <label for="rate">Oprocentowanie roczne</label>
        <div class="input-wrap"><input id="rate" name="rate" type="text" inputmode="decimal" required value="{$values.rate|escape:'html'}" placeholder="np. 7,5"><span class="suffix">%</span></div>
        <small>Od 0% do 100%; można używać przecinka</small>
      </div>
      <div class="field">
        <label for="years">Okres kredytowania</label>
        <div class="input-wrap"><input id="years" name="years" type="number" min="1" max="40" step="1" required value="{$values.years|escape:'html'}"><span class="suffix">lat</span></div>
        <small>Od 1 roku do 40 lat</small>
      </div>
      <button class="button button--primary button--wide" type="submit">Oblicz ratę <span aria-hidden="true">→</span></button>
    </form>
  </section>
  <aside class="info-panel">
    <span class="eyebrow">JAK TO DZIAŁA?</span>
    <h2>Przejrzyste obliczenia</h2>
    <p>Kalkulator wykorzystuje wzór na ratę annuitetową (równą). Dla oprocentowania 0% kwota kredytu jest dzielona przez liczbę miesięcy.</p>
    <div class="info-line"><span>Rodzaj rat</span><strong>Równe</strong></div>
    <div class="info-line"><span>Częstotliwość</span><strong>Miesięczna</strong></div>
    <div class="info-line"><span>Oprocentowanie</span><strong>Stałe</strong></div>
    <p class="fine-print">To orientacyjna symulacja bez prowizji, ubezpieczenia, zmian stóp procentowych i dodatkowych opłat. Nie stanowi oferty banku.</p>
  </aside>
</div>
{include file='layout/footer.tpl'}
