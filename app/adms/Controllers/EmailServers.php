<?php

namespace App\adms\Controllers;

use App\adms\Models\helpers\SidebarMenuPermissions;
use App\adms\Models\EmailServers as ModelsEmailServers;

class EmailServers
{
    private ?array $data = [];

    public function index(int|string|null $page = null): void
    {
        $page = (int) $page ? $page : 1;
        $emailServers = new ModelsEmailServers();
        $response = $emailServers->getEmails($page);
       
        if ($emailServers->getResult()) {
            $this->data['emailServers'] = $response;
            $this->data['pagination'] = $emailServers->getPagination();
            $this->data['sidebar_menu'] = SidebarMenuPermissions::checkPermissionsSidebarMenus();
            $this->viewEmailServers();
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header('Location: ' . URL . 'login/index'); exit;
        }
    
    }

    private function viewEmailServers(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/emailservers/emailServers", $this->data);
        $loadView->loadView();
    }
}