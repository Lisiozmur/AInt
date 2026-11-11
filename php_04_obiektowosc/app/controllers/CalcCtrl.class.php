<?php
class CalcCtrl {
    public $form;
    public $result;
    public $messages;

    // a) Konstruktor: przygotowanie obiektow roboczych.
    public function __construct() {
        $this->form = new CalcForm();
        $this->result = new CalcResult();
        $this->messages = new Messages();
    }

    // b) Pobieranie i wstepne przetworzenie danych.
    public function getParams() {
        $this->form->kwota = $this->readParam('kwota');
        $this->form->lata = $this->readParam('lata');
        $this->form->oprocentowanie = $this->readParam('oprocentowanie');
    }
    private function readParam($key) {
        $value = $_POST[$key] ?? '';
        return is_string($value) ? trim($value) : '';
    }

    // c) Walidacja danych (rozpoznaje tez przecinek jako separator dziesietny).
    public function validate() {
        if ($this->form->kwota === '' || $this->form->lata === '' || $this->form->oprocentowanie === '') {
            $this->messages->addError('Wypelnij wszystkie pola.');
            return false;
        }
        $kwota = str_replace(',', '.', $this->form->kwota);
        $lata = $this->form->lata;
        $oprocentowanie = str_replace(',', '.', $this->form->oprocentowanie);
        if (!is_numeric($kwota) || !is_numeric($oprocentowanie) || !preg_match('/^[0-9]+$/D', $lata)) {
            $this->messages->addError('Wprowadz poprawne liczby. Lata musza byc liczba calkowita.');
            return false;
        }
        $k = (float)$kwota;
        $o = (float)$oprocentowanie;
        $l = (int)$lata;
        if (!is_finite($k) || !is_finite($o) || $k <= 0 || $l < 1 || $l > 100 || $o < 0 || $o > 100) {
            $this->messages->addError('Kwota musi byc dodatnia, okres 1-100 lat, a oprocentowanie 0-100%.');
            return false;
        }
        return true;
    }

    // d) Generowanie widoku Smarty.
    public function generateView() {
        $smarty = makeSmarty();
        $smarty->assign('form', $this->form);
        $smarty->assign('result', $this->result);
        $smarty->assign('errors', $this->messages->getErrors());
        $smarty->assign('hasResult', $this->result->rataTekst !== '');
        $smarty->assign('userName', $_SESSION['login'] ?? '');
        $smarty->assign('csrfToken', Auth::token());
        $smarty->display('CalcView.tpl');
    }

    // e) Wykonanie obliczen.
    public function calculate() {
        $kwota = (float)str_replace(',', '.', $this->form->kwota);
        $lata = (int)$this->form->lata;
        $oprocentowanie = (float)str_replace(',', '.', $this->form->oprocentowanie);
        $n = $lata * 12;
        $r = $oprocentowanie / 100 / 12;
        $rata = ($r == 0.0) ? ($kwota / $n) : ($kwota * $r / (1 - pow(1 + $r, -$n)));
        if (!is_finite($rata)) {
            $this->messages->addError('Nie mozna obliczyc raty dla podanych parametrow.');
            return;
        }
        $this->result->rata = $rata;
        $this->result->rataTekst = number_format($rata, 2, ',', ' ');
    }

    // Zbiorcza metoda uruchamiana przez punkt wejscia.
    public function process() {
        Auth::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Auth::validToken($_POST['csrf'] ?? null)) {
                http_response_code(403);
                $this->messages->addError('Niepoprawny token formularza. Odswiez strone.');
            } else {
                $this->getParams();
                if ($this->validate()) {
                    $this->calculate();
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            exit('Metoda niedozwolona.');
        }
        $this->generateView();
    }
}
