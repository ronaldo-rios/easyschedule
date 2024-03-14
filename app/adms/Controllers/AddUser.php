<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AddUser as ModelsAddUser;

class AddUser
{
    private array $data = [];

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        ! empty($formData['sendAddUser']) ? $this->addUser($formData) : $this->viewAddUser();
    }

    private function addUser(array $formData): void
    {
        $addUser = new ModelsAddUser();
        $addUser->add($formData);

        if ($addUser->getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
            header("Location: " . URL . "users/index");
            exit;
        }
        else {
            $this->viewAddUser();
        }
    }

    private function viewAddUser(): void
    {
        $view = new ConfigView("adms/Views/users/addUser", $this->data);
        $view->loadView();
    }
}