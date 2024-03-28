<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AddAccessLevel as ModelsAddAccessLevel;

class AddAccessLevel
{
    private ?array $data = [];

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        ! empty($formData['sendAddAccessLevel']) 
            ? $this->addAccessLevel($formData)
            : $this->viewAddAccessLevel();
    }

    private function viewAddAccessLevel(): void
    {
        $view = new ConfigView("adms/Views/accesslevel/addAccessLevel", $this->data);
        $view->loadView();
    }

    private function addAccessLevel(?array $formData): void
    {
        $this->data = $formData;

        $addAccessLevel = new ModelsAddAccessLevel();
        $addAccessLevel->add($formData);

        if ($addAccessLevel->getResult()) {
            header("Location: " . URL . "access-levels/index");exit;
        } else {
            $this->viewAddAccessLevel();
        }
    }
}