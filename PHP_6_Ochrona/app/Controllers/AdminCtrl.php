<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\AppContext;

/** Dostęp kontroluje Router, nie ten kontroler (zadania 6 i 6b). */
final class AdminCtrl
{
    public function __construct(private AppContext $app)
    {
    }

    public function index(): void
    {
        $this->app->render('admin/index.tpl', ['page_title' => 'Panel administratora']);
    }
}
