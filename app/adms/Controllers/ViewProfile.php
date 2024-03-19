<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\ViewProfile as ModelsViewProfile;

class ViewProfile
{
    private ?array $data = [];

    public function index(): void
    {
        $profile = new ModelsViewProfile();
        $infoProfile = $profile->get();

        if ($profile->getResult()) {
            $this->data['profile'] = $infoProfile;
            $this->viewProfile();
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header('Location: ' . URL . 'login/index'); exit;
        }
    }

    private function viewProfile(): void
    {
        $view = new ConfigView("adms/Views/profile/viewProfile", $this->data);
        $view->loadView();
    }
}