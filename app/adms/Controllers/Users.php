<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ListUsers;

class Users
{
    private ?array $data = [];

    public function index(): void
    {
        $listUsers = new ListUsers();
        $users = $listUsers->list();

        $listUsers->getResult()
            ? $this->data['users'] = $users
            : $this->data['users'] = [];

        $view = new ConfigView("adms/Views/users/users", $this->data);
        $view->loadView();
    }
}