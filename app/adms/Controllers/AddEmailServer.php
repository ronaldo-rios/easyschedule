<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AddEmailServer as ModelsAddEmailServer;

class AddEmailServer
{
    private ?array $data = [];

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        ! empty($formData['sendAddEmailServers']) 
            ? $this->addEmailServer($formData) 
            : $this->viewEmailServer();
    }

    private function addEmailServer(array $formData): void
    {
        $addUser = new ModelsAddEmailServer();
        $addUser->add($formData);

        if ($addUser->getResult()) {
            header("Location: " . URL . "view-email-servers/index"); exit;
        } else {
            $this->viewEmailServer();
        }
    }

    private function viewEmailServer(): void
    {
        $view = new ConfigView("adms/Views/emailservers/addEmailServer", $this->data);
        $view->loadView();
    }

}