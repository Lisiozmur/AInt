<?php

declare(strict_types=1);

namespace App\Models;

/** Kredyt z ratami równymi, stałym oprocentowaniem nominalnym w skali roku. */
final class LoanCalculator
{
    /** @return array{months: int, monthly: float, total: float, interest: float} */
    public static function calculate(float $principal, float $annualPercentage, int $years): array
    {
        if ($principal <= 0 || $annualPercentage < 0 || $years < 1) {
            throw new \InvalidArgumentException('Nieprawidłowe dane kredytu.');
        }

        $months = $years * 12;
        $monthlyRate = $annualPercentage / 100 / 12;
        $payment = $monthlyRate == 0.0
            ? $principal / $months
            : $principal * $monthlyRate / (1 - (1 + $monthlyRate) ** (-$months));
        $total = round($payment * $months, 2);

        return [
            'months' => $months,
            'monthly' => round($payment, 2),
            'total' => $total,
            'interest' => round($total - $principal, 2),
        ];
    }
}
