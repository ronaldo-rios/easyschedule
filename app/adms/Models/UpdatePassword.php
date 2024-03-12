<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ValidatePassword;

class UpdatePassword
{
    private ?array $data;
    private ?string $key;
    private bool $result = false;
    private object $conn;


    public function getResult(): bool
    {
        return $this->result;
    }

    public function validate(?string $keyHash): void
    {
        $this->key = $keyHash;
        $this->conn = Connection::connect();
        $resultOfHash = $this->verifyKeyHash($this->key);
        $resultOfHash ? $this->result = true : $this->result = false;
    }

    public function update(?array $data): void
    {
        $this->data = $data;

        ValidateEmptyField::validateField($this->data);
        if (! ValidateEmptyField::getResult()) {
            $this->result = false;
            return;
        }

        ValidatePassword::validate($this->data['password']);
        if (! ValidatePassword::getResult()) {
            $this->result = false;
            return;
        }
        
        $this->conn = Connection::connect();
        if (! empty($this->data)) {
            $password = (string) password_hash($this->data['password'], PASSWORD_BCRYPT);
            $key = (string) $this->data['key'];
            $userInfo = $this->verifyKeyHash($key);
            $this->updatePassword($password, $key, $userInfo);
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Link inválido! Solicite um novo link.</div>";
            $this->result = false;
        }
    }

    private function verifyKeyHash(string $key): array
    {
        $query = "SELECT `id`, `name`, `email`, `recover_password` 
                  FROM `users` 
                  WHERE `recover_password` = :recover_password 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':recover_password', $key, PDO::PARAM_STR);
        $stmt->execute();
        $resultHash = $stmt->fetch(Pdo::FETCH_ASSOC);
        
        if ($stmt->rowCount() > 0) {
            return (array) $resultHash;
        }
       
        return [];
    }

    private function updatePassword(string $hashPassword, string $key, array $userInfo): void
    {
        $id = $userInfo['id'];

        $update = "UPDATE `users` 
                        SET `password` = :password,
                            `updated_at` = NOW()
                        WHERE `recover_password` = :recover_password
                        AND `id` = :id";

        $stmt = $this->conn->prepare($update);
        $stmt->bindValue(':password', $hashPassword, PDO::PARAM_STR);
        $stmt->bindValue(':recover_password', $key, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Senha atualizada com sucesso!</div>";
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Link inválido! Solicite um novo link.</div>";
            $this->result = false;
        }
    }
    
}