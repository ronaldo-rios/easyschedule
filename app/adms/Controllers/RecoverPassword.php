<?php

namespace App\adms\Controllers;

use App\adms\Models\RecoverPassword as ModelsRecoverPassword;

class RecoverPassword
{
    private string|array|null $data = null;

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        ! empty($formData['sendRecoverPassword']) ? $this->recoverPassword($formData) : $this->viewRecoverPassword();
    }

    private function viewRecoverPassword(): void
    {
        $view = new \Core\ConfigView("adms/Views/login/recoverPassword", $this->data);
        $view->loadViewLogin();
    }

    private function recoverPassword(array $formData): void
    {
        $recoverPassword = new ModelsRecoverPassword();
        $recoverPassword->recover($formData);

        if($recoverPassword->getResult()) {
            $urlRedirect = URL . "login/index";
            header("Location: $urlRedirect");
            exit;
        }
        else {
            $this->data = $recoverPassword->getResult();
            $this->viewRecoverPassword();
        }
    }
}