<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class EditPageModule
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
            ValidateEmptyField::validateField($formData, ['obs']);
            if (! ValidateEmptyField::getResult()) {
                $this->result = false;
                return;
            }

            $this->updatePageModule($formData);
        }
    }

    public function viewInfoModule(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsPageModule($id);
    }

    private function detailsPageModule(int $id): array
    {
        $query = "SELECT `id`, `type`, `name`, `obs`
                    FROM `page_modules` 
                  WHERE id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $finalResult = (array) $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $this->result = true;
            return $finalResult;
        }

        return [];
    }

    private function updatePageModule(array $formData): void
    {
        $query = "UPDATE `page_modules` 
                  SET `type` = :type, `name` = :name, 
                      `obs` = :obs, updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $formData['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':type', mb_strtolower($formData['type'], 'UTF-8'), \PDO::PARAM_STR);
        $stmt->bindValue(':name', ConvertToCapitularString::format($formData['name']), \PDO::PARAM_STR);
        $stmt->bindValue(':obs', ucfirst(mb_strtolower($formData['obs'], 'UTF-8')), \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Módulo de Página atualizado com sucesso!</div>";
            $this->result = true;
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao atualizar Módulo de página!</div>";
            $this->result = false;
        }
    }
}