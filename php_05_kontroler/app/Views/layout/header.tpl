<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Przykład aplikacji PHP: kontroler główny i kalkulator kredytowy.">
  <title>{$page_title|escape:'html'} – {$app_name|escape:'html'}</title>
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
<div class="site-shell">
  <header class="site-header">
    <a class="brand" href="{$routes.home|escape:'html'}" aria-label="Strona główna">
      <span class="brand-symbol">%</span>
      <span>Fin<span class="brand-accent">Plan</span></span>
    </a>
    <nav class="nav" aria-label="Menu główne">
      <a href="{$routes.home|escape:'html'}">Strona główna</a>
      <a href="{$routes.loanForm|escape:'html'}">Kalkulator kredytowy</a>
    </nav>
  </header>
  <main class="main">
    {foreach from=$messages item=message}
      <div class="alert alert--{$message.type|escape:'html'}" role="alert">{$message.text|escape:'html'}</div>
    {/foreach}
