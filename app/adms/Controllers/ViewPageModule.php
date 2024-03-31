<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ViewPageModule as ModelsViewPageModule;

class ViewPageModule
{
    private ?array $data = [];
    
    public function index(int|string $id): void
    {
   
        if (! empty($id)) {
            $id = (int) $id;
            $module = new ModelsViewPageModule();
            $moduleDetails = $module->viewInfoModule($id);
            if ($module->getResult()) {
              
                $this->data['viewPageModule'] = $moduleDetails;
                $this->viewModuleDetails();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Módulo não encontrado!</div>";
                header("Location: " . URL . "page-modules/index");
                exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Módulo não encontrado!</div>";
            header("Location: " . URL . "page-modules/index");
            exit;
        }
    }

    private function viewModuleDetails(): void
    {
        $view = new ConfigView("adms/Views/pageModules/viewPageModule", $this->data);
        $view->loadView();
    }
}