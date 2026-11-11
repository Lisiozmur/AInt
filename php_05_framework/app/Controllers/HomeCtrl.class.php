<?php
namespace App\Controllers;

use App\Core\App;

class HomeCtrl {
    public function show(): void {
        App::render('HomeView.tpl');
    }
}
