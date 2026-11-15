<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\LoanCalculator;
use Core\AppContext;

final class LoanCtrl
{
    public function __construct(private AppContext $app)
    {
    }

    public function form(array $values = []): void
    {
        $defaults = [
            'amount' => '300000',
            'rate' => '7,5',
            'years' => '25',
        ];
        $this->app->render('loan/form.tpl', [
            'page_title' => 'Kalkulator kredytu',
            'values' => array_replace($defaults, $values),
            'csrf_token' => csrf_token(),
        ]);
    }

    public function calculate(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            $this->app->messages->addError('Obliczenia wymagają wysłania formularza metodą POST.');
            $this->app->render('error.tpl', ['page_title' => 'Niedozwolona metoda']);
            return;
        }

        $values = [
            'amount' => post_string('amount'),
            'rate' => post_string('rate'),
            'years' => post_string('years'),
        ];

        if (!csrf_valid()) {
            http_response_code(403);
            $this->app->messages->addError('Formularz wygasł lub jest nieprawidłowy. Spróbuj ponownie.');
            $this->form($values);
            return;
        }

        $amount = decimal_number($values['amount'], 2);
        $rate = decimal_number($values['rate'], 3);
        $years = ctype_digit($values['years']) ? (int) $values['years'] : null;

        if ($amount === null || $amount < 1000 || $amount > 10000000) {
            $this->app->messages->addError('Kwota kredytu musi wynosić od 1 000 do 10 000 000 zł.');
        }
        if ($rate === null || $rate < 0 || $rate > 100) {
            $this->app->messages->addError('Oprocentowanie musi wynosić od 0% do 100%.');
        }
        if ($years === null || $years < 1 || $years > 40) {
            $this->app->messages->addError('Okres kredytowania musi wynosić od 1 do 40 lat.');
        }

        if ($this->app->messages->all() !== []) {
            http_response_code(422);
            $this->form($values);
            return;
        }

        $result = LoanCalculator::calculate($amount, $rate, $years);

        $this->app->render('loan/result.tpl', [
            'page_title' => 'Wynik obliczeń',
            'amount' => format_money($amount),
            'rate' => number_format($rate, 3, ',', ' ') . '%',
            'years' => $years,
            'months' => $result['months'],
            'monthly' => format_money($result['monthly']),
            'total' => format_money($result['total']),
            'interest' => format_money($result['interest']),
        ]);
    }
}
