<?php

namespace App\adms\Controllers;

use App\adms\Models\ListPermissions;
use Core\ConfigView;

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
            $this->data['pagination'] = $permissions->getPagination();
        } else {
            $this->data['permissions'] = [];
            $this->data['pagination'] = null;
        }

        $this->data['page'] = $page;
        $view = new ConfigView('adms/Views/permissions/permissions', $this->data);
        $view->loadView();
    }
}