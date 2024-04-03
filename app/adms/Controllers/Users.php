<?php

namespace App\adms\Controllers;

use App\adms\Models\helpers\ButtonPermissions;
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

        $buttons = [
            'add_user' => ['menu_controller' => 'add-user', 'menu_method' => 'index'],
            'view_user' => ['menu_controller' => 'view-user', 'menu_method' => 'index'],
            'edit_user' => ['menu_controller' => 'edit-user', 'menu_method' => 'index'],
            'delete_user' => ['menu_controller' => 'delete-user', 'menu_method' => 'index']
        ];

        $this->data['buttonpermissions'] = ButtonPermissions::validateButtonPermission($buttons);

        $view = new ConfigView("adms/Views/users/users", $this->data);
        $view->loadView();
    }
}