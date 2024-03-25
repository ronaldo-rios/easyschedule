<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class AddEmailServer
{
    private bool $result;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function add(?array $formData): void
    {
        $formData;
        ValidateEmptyField::validateField($formData);

        if (! ValidateEmptyField::getResult()) {
            $this->result = false;
            return;
        }

        $formData['title'] = trim($formData['title']);
        $formData['name'] = trim($formData['name']);
        $formData['password'] = password_hash($formData['password'], PASSWORD_BCRYPT);
        $formData['email'] = trim(filter_var($formData['email'], FILTER_VALIDATE_EMAIL));

        if (! $formData['email']) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email inválido!</div>";
            $this->result = false;
            return;
        }

        $this->insertEmailServer($formData);
    }

    private function insertEmailServer(?array $formData): void
    {
        $insert = "INSERT INTO `config_emails` 
                  (
                    `title`, `name`, `email`, `host`, `username`, 
                    `password`, `smtp_secure`, `port`, `created_at`
                  ) 
                  VALUES 
                  (
                    :title, :name, :email, :host, :username,
                    :password, :smtp_secure, :port, NOW()
                  )";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($insert);
        $stmt->bindValue(':title', ConvertToCapitularString::format(($formData['title'])), \PDO::PARAM_STR);
        $stmt->bindValue(':name', ConvertToCapitularString::format($formData['name']), \PDO::PARAM_STR);
        $stmt->bindValue(':email', $formData['email'], \PDO::PARAM_STR);
        $stmt->bindValue(':host', trim($formData['host']), \PDO::PARAM_STR);
        $stmt->bindValue(':username', trim($formData['username']), \PDO::PARAM_STR);
        $stmt->bindValue(':password', $formData['password'], \PDO::PARAM_STR);
        $stmt->bindValue(':smtp_secure', trim($formData['smtp_secure']), \PDO::PARAM_STR);
        $stmt->bindValue(':port', $formData['port'], \PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>E-mail Server cadastrado com sucesso!</div>";
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = 
            "<div class='alert alert-danger'>
                Houve algum erro ao tentar cadastrar E-mail Server!
            </div>";
            $this->result = false;
        }
    }
}