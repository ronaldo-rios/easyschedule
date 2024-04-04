<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\helpers\SidebarMenuPermissions;
use App\adms\Models\AccessLevels as ModelsAccessLevels;

class AccessLevels
{
    private ?array $data = [];

    public function index(): void
    {
        $accessLevels = new ModelsAccessLevels();
        $finalResult = $accessLevels->listLevels();

        $accessLevels->getResult()
            ? $this->data['accesslevels'] = $finalResult
            : $this->data['accesslevels'] = [];

        $this->data['sidebar_menu'] = SidebarMenuPermissions::checkPermissionsSidebarMenus();
        $view = new ConfigView("adms/Views/accesslevel/accessLevels", $this->data);
        $view->loadView();
    }
}