<?php

namespace Core;

class ConfigView
{
    public function __construct(private string $nameView, private array|string|null $data)
    {
    }

    public function loadView(): void
    {
        file_exists('app/' . $this->nameView . '.php') 
            ? include 'app/' . $this->nameView . '.php'
            : die("Erro ao carregar a view: {$this->nameView}. 
                Tente novamente ou entre em contato com o administrador: " . ADM_EMAIL
            );
    }
}