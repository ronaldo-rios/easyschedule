<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\UpdatePassword as ModelsUpdatePassword;

class UpdatePassword
{
    private string|array|null $data = null;
    private ?array $formData;

    public function index(): void
    {
        $keyHash = (string) filter_input(INPUT_GET, 'key', FILTER_DEFAULT);
        $this->formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
   
        !empty($keyHash) && empty($this->formData['sendUpdatePassword'])
            ? $this->validateKey($keyHash)
            : $this->updatePassword($keyHash);
    }

    private function validateKey(string $key): void
    {
        $validate = new ModelsUpdatePassword();
        $validate->validate($key);
        
        if($validate->getResult()) {
            $this->viewUpdatePassword();
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Link inválido!</div>";
            $url = URL . 'password-recover/index';
            header("Location: $url");
            exit;
        }
    }

    private function viewUpdatePassword(): void
    {
        $view = new ConfigView("adms/Views/login/updatePassword", $this->data);
        $view->loadViewLogin();
    }

    private function updatePassword(string $key): void
    {
        if (! empty($this->formData['sendUpdatePassword'])) {
            $this->formData['key'] = $key;
            $updatePass = new ModelsUpdatePassword();
            $updatePass->update($this->formData);

            if($updatePass->getResult()) {
                $url = URL . 'login/index';
                header("Location: $url");
                exit;
            }
            else {
                $this->data = $updatePass->getResult();
                $this->viewUpdatePassword();
            }
        }
        else {
            $this->viewUpdatePassword();
        }
    }
}