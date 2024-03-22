<?php

namespace App\adms\Controllers;

use App\adms\Models\EditProfile as ModelsEditProfile;

use Core\ConfigView;

class EditProfile
{
    private ?array $data = [];

    public function index(): void
    {
        $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      
        if (!empty($formData['sendEditProfile'])) {
           $this->editProfile($formData);
        } 
        else {
            $viewProfile = new ModelsEditProfile();
            $profile = $viewProfile->viewProfile();
            if ($viewProfile->getResult()) {
                $this->data['editProfile'] = $profile;
                $this->viewEditProfile();
            }
            else {
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
            }
        }
    }

    private function viewEditProfile()
    {
        $view = new ConfigView("adms/Views/profile/editProfile", $this->data);
        $view->loadView();
    }

    private function editProfile(?array $formData): void
    {
        if (! empty($formData['sendEditProfile'])) {

            $profile = new ModelsEditProfile();
            $profile->edit($formData);

            if ($profile->getResult()) {
                header("Location: " . URL . "view-profile/index");
                exit;
            }
            else {
                $this->data['editProfile'] = $formData;
                $this->viewEditProfile();
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            header("Location: " . URL . "login/index");
            exit;
        }
    }
}