<?php

namespace App\adms\Controllers;

use Core\ConfigView;

class Dashboard 
{
    private array|string|null $data;

    public function index(): void
    {
        $this->data = "Bem Vindo, {$_SESSION['user_name']}!";

        $view = new ConfigView("adms/Views/dashboard/dashboard", $this->data);
        $view->loadView();
    }
}