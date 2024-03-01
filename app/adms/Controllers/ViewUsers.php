<?php

namespace App\adms\Controllers;

use Core\ConfigView;

class ViewUsers
{
    private array $data = [];

    public function index(): void
    {
        $view = new ConfigView("adms/Views/users/viewUser", $this->data);
        $view->loadView();
    }
}