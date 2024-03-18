<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\EditUser as ModelsEditUser;

class EditUser
{
    private ?array $data = [];

    public function index(int $id): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(! empty($id) && empty($formData['sendEditUser'])) {
            $id = (int) $id;
            $user = new ModelsEditUser();
            $userDetails = $user->viewInfoUser($id);
            
            if ($user->getResult()) {
                $this->data['editUser'] = $userDetails;
                $this->viewEditUser();
            } else {
                header("Location: " . URL . "users/index");
                exit;
            }
        }
        else {
            $this->editUser($formData);
        }
    }

    private function viewEditUser(): void
    {
        $view = new ConfigView("adms/Views/users/editUser", $this->data);
        $view->loadView();
    }

    private function editUser(?array $formData): void
    {
        if (! empty($formData['sendEditUser'])) {

            $user = new ModelsEditUser();
            $user->edit($formData);

            if ($user->getResult()) {
                header("Location: " . URL . "view-user/index/{$formData['id']}");
                exit;
            }
            else {
                $this->data['editUser'] = $formData;
                $this->viewEditUser();
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header("Location: " . URL . "users/index");
            exit;
        }
    }
}