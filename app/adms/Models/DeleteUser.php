<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\DeleteDirectoryAndFiles;

class DeleteUser
{
    private object $conn;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function delete(int $id): void
    {
        $this->conn = Connection::connect();
        $user = $this->getUser($id);

        if (! empty($user)) {
            $this->deleteUser($id);

            if (! empty($user['image'])) {
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . PATH_USER_IMAGE . $user['id'] . "/";
                DeleteDirectoryAndFiles::delete($imagePath);
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Usuário não encontrado!</div>";
            $this->result = false;
        }
    }

    private function getUser(int $id): array
    {
        $query = "SELECT `id`, `image`
                  FROM users 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return (array) $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function deleteUser(int $id): void
    {
        $delete = "DELETE FROM users 
                   WHERE id = :id";

        $stmt = $this->conn->prepare($delete);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Usuário excluído com sucesso!</div>";
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao tentar excluir usuário!</div>";
            $this->result = false;
        }
    }
}