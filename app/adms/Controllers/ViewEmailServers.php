<?php

namespace App\adms\Controllers;

use App\adms\Models\ViewEmailServers as ModelsViewEmailServers;

class ViewEmailServers
{
    private ?array $data = [];

    public function index(): void
    {

        $emailServers = new ModelsViewEmailServers();
        $response = $emailServers->getEmails();
       
        if ($emailServers->getResult()) {
            $this->data['emailServers'] = $response;
            $this->viewEmailServers();
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header('Location: ' . URL . 'login/index'); exit;
        }
    
    }

    private function viewEmailServers(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/emailservers/emailServers", $this->data);
        $loadView->loadView();
    }
}