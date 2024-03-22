<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;

class EditEmailServer
{
    private array $data;
    private object $conn;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }
    
    public function viewInfoEmailServer(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsUser($id);
    }

    public function edit(?array $formData): void
    {
        $this->data = $formData;
        ValidateEmptyField::validateField($this->data);

        if (ValidateEmptyField::getResult()) {
            return;
        }

        $this->data['title'] = filter_var($this->data['title'], FILTER_DEFAULT);
        $this->data['name'] = filter_var($this->data['name'], FILTER_DEFAULT);
        $this->data['password'] = trim($this->data['password']);
        $this->data['email'] = trim(filter_var($this->data['email'], FILTER_VALIDATE_EMAIL));

        if (! $this->data['email']) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Email inválido!</div>";
            $this->result = false;
            return;
        }

        $this->updateServerEmail();
        
    }

    private function detailsUser(int $id): array
    {
        $query = "SELECT * FROM `config_emails` WHERE `id` =:id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $finalResult = (array) $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0){
            $this->result = true;
            return $finalResult;
        }

        return [];
    }

    private function updateServerEmail(): void
    {
        $update = "UPDATE `config_emails` 
                  SET `name` = :name, `host` = :host, `username` = :username, `title` = :title, 
                      `smtp_secure` = :smtp_secure,`password` = :password, 
                      `port` = :port, `updated_at` = NOW()
                  WHERE `id` = :id";

        $stmt = $this->conn->prepare($update);
        $stmt->bindParam(':id', $this->data['id'], \PDO::PARAM_INT);
        $stmt->bindParam(':title', $this->data['title'], \PDO::PARAM_STR);
        $stmt->bindParam(':name', $this->data['name'], \PDO::PARAM_STR);
        $stmt->bindParam(':host', $this->data['host'], \PDO::PARAM_STR);
        $stmt->bindParam(':user', $this->data['username'], \PDO::PARAM_STR);
        $stmt->bindParam(':smtp', $this->data['smtp_secure'], \PDO::PARAM_STR);
        $stmt->bindParam(':password', $this->data['password'], \PDO::PARAM_STR);
        $stmt->bindParam(':port', $this->data['port'], \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Email server atualizado com sucesso!</div>";
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao atualizar e-mail server!</div>";
            $this->result = false;
        }
    }
}