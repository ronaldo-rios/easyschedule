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
        $accessLevelIsNotInUse = $this->checkIfAccessLevelIsLinkedToUser($id);

        if ($accessLevelIsNotInUse) {
            $this->deleteAccessLevel($id);
        }
    }

    /**
     * Check if the access level is linked to a user. If it is, it is not possible to delete it.
     * @param int $id
     * @return void
     */
    private function checkIfAccessLevelIsLinkedToUser(int $id): bool
    {
        $select = "SELECT `id` 
                   FROM `users` 
                   WHERE access_level_id = :id";

        $stmt = $this->conn->prepare($select);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>
                Não é possível excluir este nível de acesso, pois ele está vinculado a um ou mais usuários!
            </div>";
            return $this->result = false;
        }
        else {
            return $this->result = true;
        }
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
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao tentar excluir nível de acesso!</div>";
            $this->result = false;
        }
    }
}