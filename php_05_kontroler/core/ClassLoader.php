<?php

declare(strict_types=1);

namespace Core;

/**
 * Prosty autoloader zgodny z ideą PSR-4.
 * Np. App\Controllers\LoanCtrl -> app/Controllers/LoanCtrl.php.
 */
final class ClassLoader
{
    /** @param array<string, string> $prefixes */
    public function __construct(private array $prefixes)
    {
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadClass(string $class): void
    {
        foreach ($this->prefixes as $prefix => $directory) {
            if (!str_starts_with($class, $prefix)) {
                continue;
            }

            $relative = substr($class, strlen($prefix));
            // Klasa musi składać się ze zwykłych segmentów przestrzeni nazw.
            if ($relative === '' || preg_match('/^[A-Za-z_][A-Za-z0-9_]*(\\\\[A-Za-z_][A-Za-z0-9_]*)*$/D', $relative) !== 1) {
                return;
            }

            $path = rtrim($directory, '/') . '/' . str_replace('\\', '/', $relative) . '.php';
            if (is_file($path)) {
                require_once $path;
            }
            return;
        }
    }
}
