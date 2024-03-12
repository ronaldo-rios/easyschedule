<?php

namespace App\adms\Controllers;

use Core\ConfigView;

class Error
{
    private array|string|null $data;

    public function index(): void
    {
        $this->data = "<p style='text-align: center; color: red; font-size: 1.5rem; margin-top: 10rem;'>Erro: Página não encontrada</p>";

        $view = new ConfigView("adms/Views/error/error", $this->data);
        $view->loadViewLogin();
    }
}