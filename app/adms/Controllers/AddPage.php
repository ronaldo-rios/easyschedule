<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AddPage as ModelsAddPage;

class AddPage
{
    private ?array $data = [];
    public function index()
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        ! empty($formData['sendAddPage']) 
            ? $this->addPage($formData)
            : $this->viewAddPage();
    }

    private function viewAddPage()
    {
        $dropdownAddPage = new ModelsAddPage();
        $this->data['select_status'] = $dropdownAddPage->listSelectStatus();
        $this->data['select_group'] = $dropdownAddPage->listSelectPageGroupId();
        $this->data['select_module'] = $dropdownAddPage->listSelectModuleId();
        $view = new ConfigView("adms/Views/pages/addPage", $this->data);
        $view->loadView();
    }

    private function addPage(array $formData)
    {
        $addPage = new ModelsAddPage();
        $addPage->add($formData);

        if ($addPage->getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Página cadastrada com sucesso!</div>";
            header("Location: " . URL . "pages/index");
            exit;
        }
        else {
            $this->viewAddPage();
        }
    }
}