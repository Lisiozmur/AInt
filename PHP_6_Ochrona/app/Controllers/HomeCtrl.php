<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\AppContext;

final class HomeCtrl
{
    public function __construct(private AppContext $app)
    {
    }

    public function index(): void
    {
        $this->app->render('home.tpl', ['page_title' => 'Strona główna']);
    }
}
