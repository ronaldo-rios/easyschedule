<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ViewUser as ModelsViewUser;

class ViewUser
{
    private ?array $data = [];

    public function index(int $id): void
    {
        if (! empty($id)) {
            $idUser = (int) $id;
            $user = new ModelsViewUser();
            $userDetails = $user->viewInfo($idUser);
           
            if ($user->getResult()) {
                $this->data['viewUser'] = $userDetails;
                $this->viewUserDetails();
            } else {
                header("Location: " . URL . "users/index");
                exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header("Location: " . URL . "users/index");
            exit;
        }
    }

    private function viewUserDetails(): void
    {
        $view = new ConfigView("adms/Views/users/viewUser", $this->data);
        $view->loadView();
    }
}