{include file='layout/header.tpl'}
<section class="page-intro">
  <span class="eyebrow">APLIKACJA PHP</span>
  <h1>{$page_title|escape:'html'}</h1>
  <p>Wybierz jedną z dostępnych stron za pomocą menu.</p>
  <a class="button button--primary" href="{$routes.home|escape:'html'}">Powrót do strony głównej</a>
</section>
{include file='layout/footer.tpl'}
