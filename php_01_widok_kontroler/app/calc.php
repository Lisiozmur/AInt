<?php
// KONTROLER kalkulatora kredytowego
require_once dirname(__FILE__).'/../config.php';

$messages = array();
$kwota = isset($_POST['kwota']) ? trim($_POST['kwota']) : '';
$lata = isset($_POST['lata']) ? trim($_POST['lata']) : '';
$oprocentowanie = isset($_POST['oprocentowanie']) ? trim($_POST['oprocentowanie']) : '';

// Walidacja wykonuje się dopiero po wysłaniu formularza.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($kwota === '' || $lata === '' || $oprocentowanie === '') {
        $messages[] = 'Wypełnij wszystkie pola.';
    } elseif (!is_numeric($kwota) || !is_numeric($lata) || !is_numeric($oprocentowanie)) {
        $messages[] = 'Wprowadź poprawne liczby.';
    } elseif ($kwota <= 0 || $lata <= 0 || $oprocentowanie < 0) {
        $messages[] = 'Kwota i liczba lat muszą być dodatnie, a oprocentowanie nie może być ujemne.';
    } elseif (filter_var($lata, FILTER_VALIDATE_INT) === false) {
        $messages[] = 'Liczba lat musi być liczbą całkowitą.';
    } else {
        // Obliczenie miesięcznej raty równej (annuitetowej).
        $liczba_rat = (int)$lata * 12;
        $r = (float)$oprocentowanie / 100 / 12;

        if ($r == 0) {
            $rata = (float)$kwota / $liczba_rat;
        } else {
            $rata = (float)$kwota * $r / (1 - pow(1 + $r, -$liczba_rat));
        }
    }
}

// Kontroler przekazuje dane do widoku.
include dirname(__FILE__).'/calc_view.php';
