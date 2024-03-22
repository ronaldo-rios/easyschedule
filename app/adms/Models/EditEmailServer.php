<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

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

}