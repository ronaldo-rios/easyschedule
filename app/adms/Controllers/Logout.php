<?php

namespace App\adms\Controllers;

class Logout 
{
    public function index(): void
    {
        unset(
            $_SESSION['user_name'], 
            $_SESSION['user_email'], 
            $_SESSION['user_id'], 
            $_SESSION['user_nickname'], 
            $_SESSION['user_image']
        );     

        $_SESSION['msg'] = "<div class='alert alert-success'>Logout realizado com sucesso!</div>";
        header("Location: " . URL . "login/index");
        exit;
    }
}