<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\helpers\SidebarMenuPermissions;
use App\adms\Models\ListPageGroups as ModelsListPageGroups;

class PageGroups
{
    private array $data;

    public function index(int|string|null $page = null): void
    {
        $page = (int) $page ? $page : 1;
        $pageGroups = new ModelsListPageGroups();
        $users = $pageGroups->list($page);

        if($pageGroups->getResult()){
            $this->data['page_groups'] = $users;
            $this->data['pagination'] = $pageGroups->getPagination();
        } else {
            $this->data['page_groups'] = [];
        }
        $this->data['sidebar_menu'] = SidebarMenuPermissions::checkPermissionsSidebarMenus();
        $this->viewPageGroups();
    }

    private function viewPageGroups(): void
    {
        $view = new ConfigView("adms/Views/pageGroups/pageGroups", $this->data);
        $view->loadView();
    }

}