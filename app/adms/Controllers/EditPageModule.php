<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\EditPageModule as ModelsEditPageModule;

class EditPageModule
{
    private ?array $data;

    public function index(string|int $id)
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(! empty($id) && empty($formData['sendEditPageModule'])) {
            $id = (int) $id;
            $editPageModule = new ModelsEditPageModule();
            $details = $editPageModule->viewInfoModule($id);
            
            if ($editPageModule->getResult()) {
                $this->data['editModule'] = $details;
                $this->viewEditPageModule();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Módulo não encontrado!</div>";
                header("Location: " . URL . "page-modules/index");exit;
            }
        }
        else {
            $this->editPageGroup($formData);
        }
    }

    private function viewEditPageModule(): void
    {
        $view = new ConfigView("adms/Views/pageModules/editPageModule", $this->data);
        $view->loadView();
    }

    private function editPageGroup(?array $formData): void
    {
        if (! empty($formData['sendEditPageModule'])) {
            $module = new ModelsEditPageModule();
            $module->edit($formData);
            if ($module->getResult()) {
                header("Location:" . URL . "view-page-module/index/{$formData['id']}");exit;
            }
            else {
                $this->data['editModule'] = $formData;
                $this->viewEditPageModule();
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Módulo não encontrado!</div>";
            header("Location: " . URL . "page-modules/index");exit;
        }
    }
}