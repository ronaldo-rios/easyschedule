<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ListPageModules as ModelsListPageModules;

class PageModules
{
    private ?array $data = [];

    public function index(int|string|null $page = null): void
    {
        $page = (int) $page ? $page : 1;
        $modules = new ModelsListPageModules();
        $listModules = $modules->list($page);

        if($modules->getResult()){
            $this->data['modules'] = $listModules;
            $this->data['pagination'] = $modules->getPagination();
        } else {
            $this->data['modules'] = [];
        }

        $this->viewPageModules();
    }

    private function viewPageModules(): void
    {
        $view = new ConfigView("adms/Views/pageModules/pageModules", $this->data);
        $view->loadView();
    }
}