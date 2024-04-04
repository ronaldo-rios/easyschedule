<?php

namespace App\adms\Controllers;

use App\adms\Models\helpers\SidebarMenuPermissions;
use Core\ConfigView;

class Dashboard 
{
    private array|string|null $data;

    public function index(): void
    {
        $this->data['welcome'] = "Bem Vindo(a), {$_SESSION['user_name']}!";
        $this->data['sidebar_menu'] = SidebarMenuPermissions::checkPermissionsSidebarMenus();

        $view = new ConfigView("adms/Views/dashboard/dashboard", $this->data);
        $view->loadView();
    }
}