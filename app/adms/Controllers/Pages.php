<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ListPages;

class Pages
{
    private ?array $data = [];

    public function index(int|string|null $page = null): void
    {
        $page = (int) $page ? $page : 1;
        $pages = new ListPages();
        $listPages = $pages->list($page);

        if($pages->getResult()){
            $this->data['pages'] = $listPages;
            $this->data['pagination'] = $pages->getPagination();
        } else {
            $this->data['pages'] = [];
        }

        $this->viewPages();
    }

    private function viewPages(): void
    {
        $view = new ConfigView("adms/Views/pages/pages", $this->data);
        $view->loadView();
    }
}