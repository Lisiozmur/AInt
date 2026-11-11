<?php
namespace App\Core;

// App\Controllers\CalcCtrl => app/Controllers/CalcCtrl.class.php
class ClassLoader {
    public function __construct(private string $appDir) {}

    public function register(): void {
        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadClass(string $class): void {
        $prefix = 'App\\';
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) return;
        $relative = substr($class, strlen($prefix));
        $path = $this->appDir . '/' . str_replace('\\', '/', $relative) . '.class.php';
        if (is_file($path)) require_once $path;
    }
}
