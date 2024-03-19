<?php

namespace App\adms\Controllers;

use App\adms\Models\DeleteUser as ModelsDeleteUser;

class DeleteUser
{
    public function index(int $id): void
    {
        if (!empty($id)) {
            $userId = (int) $id;
            $deleteUser = new ModelsDeleteUser();
            $deleteUser->delete($userId);
            
            if ($deleteUser->getResult()) {
                header('Location: ' . URL . 'users/index'); exit;
            }
            else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao tentar excluir usuário!</div>";
                header('Location: ' . URL . 'users/index'); exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header('Location: ' . URL . 'users/index'); exit;
        }

    }
}