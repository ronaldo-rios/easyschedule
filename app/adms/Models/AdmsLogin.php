<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;

class AdmsLogin
{
    private ?array $data;
    private object $conn;
    private bool $result;
   

    public function getResult(): bool
    {
        return $this->result;
    }

    public function login(array $data = null): void
    {
        $this->data = $data;
        $this->conn = Connection::connect();

        $sqlUser = $this->conn->prepare($this->validateUser());
        $sqlUser->bindValue(':user', trim($this->data['user']), PDO::PARAM_STR);
        $sqlUser->execute();
        $resultUser = $sqlUser->fetch();

        if ($resultUser) {
            $this->validatePassword($resultUser);
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário ou senha incorretos</div>";
            $this->result = false;
        }
    }

    private function validateUser(): string
    {
        return "SELECT `id`, `name`, `nickname`, `email`, `password`, `image` 
                FROM `users` 
                    WHERE UPPER(`user`) = UPPER(:user)
                    LIMIT 1";
    }

    private function validatePassword(?array $resultUser): void
    {
        if (password_verify($this->data['password'], $resultUser['password'])) {
            $_SESSION['user_id'] = $resultUser['id'];
            $_SESSION['user_name'] = $resultUser['name'];
            $_SESSION['user_nickname'] = $resultUser['nickname'];
            $_SESSION['user_email'] = $resultUser['email'];
            $_SESSION['user_image'] = $resultUser['image'];
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário ou senha incorretos</div>";
            $this->result = false;
        }
    }
}