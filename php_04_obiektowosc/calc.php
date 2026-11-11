<?php
// Punkt wejscia kalkulatora; kontroler obsluguje GET i POST.
require_once __DIR__ . '/config.php';
$ctrl = new CalcCtrl();
$ctrl->process();
