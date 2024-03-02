<?php

namespace App\adms\Controllers;

use Core\ConfigView;

class NewUser
{
    private string|array|null $data = null;
    private ?array $formData;

    public function index()
    {
        $this->formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(! empty($this->formData['sendNewUser'])) {
            $register = new \App\adms\Models\AdmsNewUser();
            $register->create($this->formData);

            if($register->getResult()) {
                $urlRedirect = URL . "login/index";
                header("Location: $urlRedirect");
                exit;
            }
            else {
                $this->data = $register->getResult();
                $this->viewNewUser();
            }
        }
        else {
            $this->viewNewUser();
        }

    }

    private function viewNewUser(): void
    {
        $view = new ConfigView("adms/Views/login/newUser", $this->data);
        $view->loadView();
    }
}