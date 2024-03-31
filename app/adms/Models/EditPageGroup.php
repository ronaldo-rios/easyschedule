<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class EditPageGroup
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

            $this->updatePageGroup($formData);
        }
    }

    public function viewInfoGroup(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsPageGroup($id);
    }

    private function detailsPageGroup(int $id): array
    {
        $query = "SELECT `id`, `group_name`
                    FROM `page_groups` 
                  WHERE id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $finalResult = (array) $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $this->result = true;
            return $finalResult;
        }

        return [];
    }

    private function updatePageGroup(array $formData): void
    {
        $query = "UPDATE `page_groups` 
                  SET group_name = :group_name, updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $formData['id'], \PDO::PARAM_INT);
        $stmt->bindParam(':group_name', ConvertToCapitularString::format($formData['group_name']), \PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Grupo de Página atualizado com sucesso!</div>";
            $this->result = true;
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao atualizar Grupo de página!</div>";
            $this->result = false;
        }
    }
}