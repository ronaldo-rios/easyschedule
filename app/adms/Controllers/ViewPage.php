<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ViewPage as ModelsViewPage;

class viewPage
{
    private ?array $data = [];

    public function index(int|string $id):void
    {
        if (! empty($id)) {
            $id = (int) $id;
            $page = new ModelsViewPage();
            $pageDetails = $page->viewInfoPage($id);
            if ($page->getResult()) {
              
                $this->data['viewPage'] = $pageDetails;
                $this->viewPageDetails();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada!</div>";
                header("Location: " . URL . "pages/index");
                exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada!</div>";
            header("Location: " . URL . "pages/index");
            exit;
        }
    }

    private function viewPageDetails():void
    {
        $view = new ConfigView("adms/Views/pages/viewPage", $this->data);
        $view->loadView();
    }
}