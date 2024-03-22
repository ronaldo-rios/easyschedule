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

        if(! empty($id) && empty($formData['sendEditUser'])) {
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
            echo "Edit email server";
        }

    }

    private function viewEditEmailServer(): void
    {
        $view = new ConfigView("adms/Views/emailservers/editEmailServer", $this->data);
        $view->loadView();
    }
}