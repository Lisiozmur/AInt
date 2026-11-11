<?php
namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Model\CalcForm;
use App\Model\CalcResult;
use App\Model\Messages;

class CalcCtrl {
    public CalcForm $form;
    public CalcResult $result;
    public Messages $messages;

    // a. konstruktor
    public function __construct() {
        $this->form = new CalcForm();
        $this->result = new CalcResult();
        $this->messages = App::messages();
    }
    // b. pobieranie parametrow
    public function getParams(): void {
        $this->form->kwota = requestText($_POST, 'kwota');
        $this->form->lata = requestText($_POST, 'lata');
        $this->form->oprocentowanie = requestText($_POST, 'oprocentowanie');
    }
    // c. walidacja
    public function validate(): bool {
        if ($this->form->kwota === '' || $this->form->lata === '' || $this->form->oprocentowanie === '') {
            $this->messages->addError('Wypelnij wszystkie pola.');
            return false;
        }
        $kwota = str_replace(',', '.', $this->form->kwota);
        $oprocentowanie = str_replace(',', '.', $this->form->oprocentowanie);
        if (!is_numeric($kwota) || !is_numeric($oprocentowanie) || !preg_match('/^[0-9]+$/D', $this->form->lata)) {
            $this->messages->addError('Wprowadz poprawne liczby. Lata musza byc calkowite.');
            return false;
        }
        $k = (float)$kwota;
        $o = (float)$oprocentowanie;
        $l = (int)$this->form->lata;
        if (!is_finite($k) || !is_finite($o) || $k <= 0 || $l < 1 || $l > 100 || $o < 0 || $o > 100) {
            $this->messages->addError('Kwota musi byc dodatnia, okres 1-100 lat, oprocentowanie 0-100%.');
            return false;
        }
        return true;
    }
    // d. wyswietlanie widoku
    public function generateView(): void {
        App::render('CalcView.tpl', [
            'form' => $this->form,
            'result' => $this->result,
            'errors' => $this->messages->getErrors(),
            'hasResult' => $this->result->rataTekst !== '',
        ]);
    }
    // e. praca kalkulatora
    public function calculate(): void {
        if (!Auth::validToken($_POST['csrf'] ?? null)) {
            http_response_code(403);
            $this->messages->addError('Niepoprawny token formularza.');
        } else {
            $this->getParams();
            if ($this->validate()) {
                $k = (float)str_replace(',', '.', $this->form->kwota);
                $n = ((int)$this->form->lata) * 12;
                $r = (float)str_replace(',', '.', $this->form->oprocentowanie) / 1200;
                $rata = $r == 0.0 ? $k / $n : $k * $r / (1 - pow(1 + $r, -$n));
                if (is_finite($rata)) {
                    $this->result->rata = $rata;
                    $this->result->rataTekst = number_format($rata, 2, ',', ' ');
                } else {
                    $this->messages->addError('Nie mozna obliczyc raty.');
                }
            }
        }
        $this->generateView();
    }
    // akcja wyswietlenia pustego formularza
    public function show(): void {
        $this->generateView();
    }
}
