<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\DeleteAccessLevel as ModelsDeleteAccessLevel;

class DeleteAccessLevel
{

    public function index(string|int $id)
    {
        $id = (int) $id;
        if (!empty($id)) {
            $delete = new ModelsDeleteAccessLevel();
            $delete->delete($id);
            
            if ($delete->getResult()) {
                header('Location: ' . URL . 'access-levels/index'); exit;
            }
            else {
                $_SESSION['msg'] = 
                "<div class='alert alert-danger'>
                    Erro ao tentar excluir nível de acesso!
                </div>";
                header('Location: ' . URL . 'access-levels/index'); exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de Acesso não encontrado!</div>";
            header('Location: ' . URL . 'access-levels/index'); exit;
        }
    }
}