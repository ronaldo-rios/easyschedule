<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

class AdmsNewUser 
{
    private ?array $data;
    private object $conn;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function create(?array $data): void
    {
        $this->data = $data;
        $this->conn = Connection::connect();

        $sqlUser = $this->conn->prepare($this->queryUser());
        $sqlUser->bindValue(':user', $this->data['user'], \PDO::PARAM_STR);
        $sqlUser->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
        $sqlUser->execute();
        $ifExists = $sqlUser->fetch();

        if ($ifExists) {
            $this->verifyIfEmailExists($ifExists);
            $this->verifyIfUserExists($ifExists);
        }

        if(! $ifExists) {

            $encriptPassword = password_hash($this->data['password'], PASSWORD_BCRYPT);

            $sqlInsert = $this->conn->prepare($this->insertUser());
            $sqlInsert->bindValue(':name', $this->data['name'], \PDO::PARAM_STR);
            $sqlInsert->bindValue(':nickname', $this->data['nickname'], \PDO::PARAM_STR);
            $sqlInsert->bindValue(':email', $this->data['email'], \PDO::PARAM_STR);
            $sqlInsert->bindValue(':user', trim($this->data['user']), \PDO::PARAM_STR);
            $sqlInsert->bindValue(':password', $encriptPassword, \PDO::PARAM_STR);
            $sqlInsert->execute();

            if($sqlInsert->rowCount()) {
                $_SESSION['msg'] = "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
                $this->result = true;
            } 
            else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar usuário!</div>";
                $this->result = false;
            }
        } 

    }

    private function queryUser(): string
    {
        return "SELECT `id`, `user`, `email` 
            FROM `users` 
            WHERE 
            (UPPER(`user`) = UPPER(:user)) OR (`email` = :email)
            LIMIT 1";
    }

    private function verifyIfEmailExists(?array $userExists): void
    {
        if($userExists['email'] && $userExists['email'] === $this->data['email']) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email já existe. Tente outro e-mail.</div>";
            $this->result = false;
        } 
    }

    private function verifyIfUserExists(?array $userExists): void
    {
        if($userExists['user'] && $userExists['user'] === $this->data['user']) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário já existe!</div>";
            $this->result = false;
        }
    }

    private function insertUser(): string
    {
        return "INSERT INTO `users` 
            (`name`, `nickname`, `email`, `user`, `password`, `created_at`) 
            VALUES (:name, :nickname, :email, :user, :password, NOW())";
    }
}