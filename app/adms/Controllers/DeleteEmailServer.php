<?php

namespace App\adms\Controllers;

use App\adms\Models\DeleteEmailServer as ModelsDeleteEmailServer;

class DeleteEmailServer
{
    public function index(int|string $id): void
    {
        $id = (int) $id;
        if (!empty($id)) {
            $delete = new ModelsDeleteEmailServer();
            $delete->delete($id);
            
            if ($delete->getResult()) {
                header('Location: ' . URL . 'view-email-servers/index'); exit;
            }
            else {
                $_SESSION['msg'] = 
                "<div class='alert alert-danger'>
                    Erro ao tentar excluir servidor de e-mail!
                </div>";
                header('Location: ' . URL . 'view-email-servers/index'); exit;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Servidor de E-mail Não encontrado!</div>";
            header('Location: ' . URL . 'view-email-servers/index'); exit;
        }
    }

}