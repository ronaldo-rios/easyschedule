<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\NewConfirmEmail as ModelsNewConfirmEmail;

class NewConfirmEmail
{
    private string|array|null $data = null;
    private ?array $formData;

    public function index(): void
    {
        $this->formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        ! empty($this->formData['sendNewConfirmEmail'])
            ? $this->newConfirmEmail()
            : $this->viewNewConfirmEmail();
    }

    private function viewNewConfirmEmail(): void
    {
        $view = new ConfigView("adms/Views/login/newConfirmEmail", $this->data);
        $view->loadView();
    }

    private function newConfirmEmail(): void
    {
        $newConfirmEmail = new ModelsNewConfirmEmail();
        $newConfirmEmail->newConfirmEmail($this->formData);

        if ($newConfirmEmail->getResult()) {
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
            exit;
        }
        else {
            $this->data = $newConfirmEmail->getResult();
            $this->viewNewConfirmEmail();
        }
    }
}