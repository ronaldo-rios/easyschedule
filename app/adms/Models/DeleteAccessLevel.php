<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

class DeleteAccessLevel
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function delete(int $id): void
    {
        $this->conn = Connection::connect();
        $this->deleteAccessLevel($id);
    }

    private function deleteAccessLevel(int $id): void
    {
        $delete = "DELETE FROM `access_levels` 
                   WHERE id = :id";

        $stmt = $this->conn->prepare($delete);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Nível de acesso excluído com sucesso!</div>";
            $this->result = true;
        }
        else {
            $this->result = false;
        }
    }
}