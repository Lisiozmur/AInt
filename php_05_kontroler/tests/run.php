<?php

declare(strict_types=1);

// Testy logiki bez zewnętrznych zależności (mogą działać przed composer install).
require __DIR__ . '/../core/ClassLoader.php';
$loader = new Core\ClassLoader(['Core\\' => __DIR__ . '/../core/', 'App\\' => __DIR__ . '/../app/']);
$loader->register();
require __DIR__ . '/../core/functions.php';

function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('BŁĄD: ' . $message);
    }
    echo 'OK: ' . $message . PHP_EOL;
}

check(class_exists(App\Models\LoanCalculator::class), 'ClassLoader ładuje klasę modelu');
check(class_exists(Core\Messages::class), 'ClassLoader ładuje klasę Core');
check(app_url('loanForm') === 'index.php?action=loanForm', 'link do kontrolera głównego');
check(decimal_number('7,5') === 7.5, 'przecinek w oprocentowaniu');
check(decimal_number('x123') === null, 'odrzucenie niepoprawnej liczby');
check(decimal_number('5,123', 3) === 5.123, 'trzy miejsca w oprocentowaniu');
$zero = App\Models\LoanCalculator::calculate(1200, 0, 1);
check($zero['months'] === 12 && $zero['monthly'] === 100.0 && $zero['interest'] === 0.0, 'kredyt 0%');
$example = App\Models\LoanCalculator::calculate(100000, 12, 1);
check(abs($example['monthly'] - 8884.88) < 0.02, 'rata kredytu oprocentowanego');
check($example['interest'] > 0 && $example['total'] > 100000, 'odsetki i suma rat');
echo 'WSZYSTKIE TESTY ZAKOŃCZONE POMYŚLNIE' . PHP_EOL;
