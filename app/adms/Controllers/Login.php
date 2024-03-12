<?php

namespace App\adms\Controllers;

use Core\ConfigView;
use App\adms\Models\AdmsLogin;

class Login
{
    private ?array $formData;
    private array|string|null $data = null;

    public function index(): void
    {
        $this->formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (! empty($this->formData['sendLogin'])) {
            $validateLogin = new AdmsLogin();
            $validateLogin->login($this->formData);
            
            if($validateLogin->getResult()) {
                    $urlRedirect = URL . "dashboard/index";
                    header("Location: $urlRedirect");
                    exit;
            } 
            else {
                $this->data = $validateLogin->getResult();
            }
        } 

        $view = new ConfigView("adms/Views/login/login", null);
        $view->loadViewLogin();
    }
}