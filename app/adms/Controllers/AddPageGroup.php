<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AddPageGroup as ModelsAddPageGroup;

class AddPageGroup
{
    private ?array $data = []; 

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        ! empty($formData['sendAddPageGroup']) 
            ? $this->addPageGroup($formData)
            : $this->viewAddPageGroup();
    }

    private function addPageGroup(?array $formData): void
    {
        $this->data = $formData;

        $addPageGroup = new ModelsAddPageGroup();
        $addPageGroup->add($formData);

        if ($addPageGroup->getResult()) {
            header("Location: " . URL . "page-groups/index");exit;
        } else {
            $this->viewAddPageGroup();
        }
    }

    private function viewAddPageGroup(): void
    {
        $view = new ConfigView("adms/Views/pageGroups/addPageGroup", $this->data);
        $view->loadView();
    }
}