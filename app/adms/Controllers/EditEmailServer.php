<?php

namespace App\adms\Controllers;

use App\adms\Models\EditEmailServer as ModelsEditEmailServers;
use Core\ConfigView;

class EditEmailServer
{
    private ?array $data;

    public function index(int|string $id)
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(! empty($id) && empty($formData['sendEditEmailServers'])) {
            $id = (int) $id;
            $emailServer = new ModelsEditEmailServers();
            $details = $emailServer->viewInfoEmailServer($id);
            
            if ($emailServer->getResult()) {
                $this->data['editEmailServer'] = $details;
                $this->viewEditEmailServer();
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Email server não encontrado!</div>";
                $urlRedirect = URL . "view-email-servers/index";
                header("Location: $urlRedirect");
            }
        }
        else {
            $this->editEmailServer($formData);
        }

    }

    private function viewEditEmailServer(): void
    {
        $view = new ConfigView("adms/Views/emailservers/editEmailServer", $this->data);
        $view->loadView();
    }

    private function editEmailServer(?array $formData): void
    {
        if (! empty($formData['sendEditEmailServers'])) {
            $emailServer = new ModelsEditEmailServers();
            $emailServer->edit($formData);
            if ($emailServer->getResult()) {
                header("Location:" . URL . "view-email-servers/index");exit;
            }
            else {
                $this->data['editEmailServer'] = $formData;
                $this->viewEditEmailServer();
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email server não encontrado!</div>";
            $urlRedirect = URL . "view-email-servers/index";
            header("Location: $urlRedirect");
        }
    }
}