<?php

namespace App\adms\Controllers;

use App\adms\Models\ConfirmEmail as ModelsConfirmEmail;

class ConfirmEmail
{
    public function index(): void
    {
        $key = (string) filter_input(INPUT_GET, "key", FILTER_DEFAULT);
        if (! empty($key)) {
            $this->validateKey($key);
        }
    }

    private function validateKey(string $key): void
    {
        $confirmEmail = new ModelsConfirmEmail();
        $confirmEmail->confirm($key);

        if($confirmEmail->getResult()) {
            $url = URL . 'login/index';
            header("Location: $url");
            exit;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Link inválido!</div>";
            $url = URL . 'login/index';
            header("Location: $url");
            exit;
        }
    }
}