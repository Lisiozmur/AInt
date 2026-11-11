<?php
// Punkt wejscia logowania i wylogowania; brak kodu HTML.
require_once __DIR__ . '/config.php';
$ctrl = new LoginCtrl();
$ctrl->process();
