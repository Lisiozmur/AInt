<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Kalkulator kredytowy PHP: router, logowanie, role, Smarty i autoloading.">
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
      {if $is_logged_in}
        <a href="{$routes.loanForm|escape:'html'}">Kalkulator</a>
        {if $current_role == 'admin'}
          <a href="{$routes.admin|escape:'html'}">Panel administratora</a>
        {/if}
        <span class="user-chip" title="Aktualna rola">{$current_user|escape:'html'} · {$current_role|escape:'html'}</span>
        <form class="logout-form" method="post" action="{$routes.logout|escape:'html'}">
          <input type="hidden" name="csrf_token" value="{$csrf_token|escape:'html'}">
          <button class="nav-button" type="submit">Wyloguj</button>
        </form>
      {else}
        <a href="{$routes.login|escape:'html'}">Zaloguj się</a>
      {/if}
    </nav>
  </header>
  <main class="main">
    {foreach from=$messages item=message}
      <div class="alert alert--{$message.type|escape:'html'}" role="alert">{$message.text|escape:'html'}</div>
    {/foreach}
