<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AddPageModule as ModelsAddPageModule;

class AddPageModule
{
    private ?array $data = []; 

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        ! empty($formData['sendAddPageModule']) 
            ? $this->addPageModule($formData)
            : $this->viewAddPageModule();
    }

    private function addPageModule(?array $formData): void
    {
        $this->data = $formData;

        $addPageGroup = new ModelsAddPageModule();
        $addPageGroup->add($formData);

        if ($addPageGroup->getResult()) {
            header("Location: " . URL . "page-modules/index");exit;
        } else {
            $this->viewAddPageModule();
        }
    }

    private function viewAddPageModule(): void
    {
        $view = new ConfigView("adms/Views/pageModules/addPageModule", $this->data);
        $view->loadView();
    }
}