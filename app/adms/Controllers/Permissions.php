<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ListPermissions;
use App\adms\Models\helpers\SidebarMenuPermissions;

class Permissions
{
    private ?array $data = [];

    public function index(string|int|null $page = null): void
    {
        $accessLevelId = (int) filter_input(INPUT_GET, 'level', FILTER_SANITIZE_NUMBER_INT);
        $page = (int) $page ? $page : 1;
       
        $permissions = new ListPermissions();
        $resultPermissions = $permissions->listPermissions($accessLevelId, $page);
        
        if ($permissions->getResult()) {
            $this->data['permissions'] = $resultPermissions;
            $this->data['access_level'] = $permissions->getAccessLevel();
            $this->data['pagination'] = $permissions->getPagination();
        } else {
            $this->data['permissions'] = [];
            $this->data['access_level'] = '';
            $this->data['pagination'] = null;
        }

        $this->data['page'] = $page;
        $this->data['sidebar_menu'] = SidebarMenuPermissions::checkPermissionsSidebarMenus();

        $view = new ConfigView('adms/Views/permissions/permissions', $this->data);
        $view->loadView();
    }
}