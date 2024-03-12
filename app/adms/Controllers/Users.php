<?php

namespace App\adms\Controllers;

use Core\ConfigView;

class Users
{
    private ?array $data = [];

    public function index(): void
    {
        $view = new ConfigView("adms/Views/users/users", $this->data);
        $view->loadView();
    }
}