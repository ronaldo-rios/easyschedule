<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ConvertToCapitularString;
use App\adms\Models\helpers\ValidateEmptyField;

class EditAccessLevel
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function edit(?array $formData): void
    {
        if ($formData) {
            $this->conn = Connection::connect();
            ValidateEmptyField::validateField($formData);
            if (! ValidateEmptyField::getResult()) {
                $this->result = false;
                return;
            }

            $this->updateAccessLevel($formData);
        }
    }

    public function viewInfoAccessLevel(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsLevel($id);
    }

    private function detailsLevel(int $id): array
    {
        $query = "SELECT * FROM `access_levels` 
                  WHERE id = :id AND order_level > :order_level
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->bindParam(':order_level', $_SESSION['order_level'], \PDO::PARAM_INT);
        $stmt->execute();
        $finalResult = (array) $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $this->result = true;
            return $finalResult;
        }

        return [];
    }

    private function updateAccessLevel(array $formData): void
    {
        $query = "UPDATE `access_levels` 
                  SET access_level = :access_level, updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $formData['id'], \PDO::PARAM_INT);
        $stmt->bindParam(':access_level', ConvertToCapitularString::format($formData['name']), \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Nível de acesso atualizado com sucesso!</div>";
            $this->result = true;
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: Nível de acesso não atualizado!</div>";
            $this->result = false;
        }
    }
}