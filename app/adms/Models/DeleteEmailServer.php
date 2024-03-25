<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

class DeleteEmailServer
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
        $emalServer = $this->queryEmailServer($id);

        if (! empty($emalServer)) {
            $this->deleteEmailServer($id);
            $this->result = true;
        }
    }

    private function deleteEmailServer(int $id): void
    {
        $delete = "DELETE FROM `config_emails` WHERE id = :id";

        $stmt = $this->conn->prepare($delete);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Servidor de email excluído com sucesso!</div>";
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao tentar excluir servidor de email!</div>";
            $this->result = false;
        }
    }

    private function queryEmailServer(int $id): array
    {
        $sqlquery = "SELECT `id`
                        FROM `config_emails` 
                        WHERE id = :id";

        $stmt = $this->conn->prepare($sqlquery);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return (array) $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}