<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ListUsers;

class Users
{
    private ?array $data = [];

    public function index(int|string|null $page = null): void
    {
        $page = (int) $page ? $page : 1;
        $listUsers = new ListUsers();
        $users = $listUsers->list($page);

        if($listUsers->getResult()){
            $this->data['users'] = $users;
            $this->data['pagination'] = $listUsers->getPagination();
        } else {
            $this->data['users'] = [];
        }

        $view = new ConfigView("adms/Views/users/users", $this->data);
        $view->loadView();
    }
}