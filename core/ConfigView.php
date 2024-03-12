<?php

namespace Core;

class ConfigView
{
    public function __construct(private string $nameView, private array|string|null $data)
    {
    }

    public function loadView(): void
    {
        if(file_exists('app/' . $this->nameView . '.php')) {
            include 'app/adms/Views/include/head.php';
            include 'app/adms/Views/include/main.php';
            include 'app/' . $this->nameView . '.php';
            include 'app/adms/Views/include/footer.php';
        } else {
            die("Erro ao carregar a view: {$this->nameView}. 
                Tente novamente ou entre em contato com o administrador: " . ADM_EMAIL
            );
        }
    }

    /**
     * Pages that do not require the main.php file to be loaded
     * @return void
     */ 
    public function loadViewLogin(): void
    {
        if(file_exists('app/' . $this->nameView . '.php')) {
            include 'app/adms/Views/include/head.php';
            include 'app/' . $this->nameView . '.php';
            include 'app/adms/Views/include/footer.php';
        } else {
            die("Erro ao carregar a view: {$this->nameView}. 
                Tente novamente ou entre em contato com o administrador: " . ADM_EMAIL
            );
        }
    }
}