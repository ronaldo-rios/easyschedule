<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

class AccessLevels
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function listLevels(): ?array
    {
        $this->conn = Connection::connect();
        return $this->queryAccessLevels();
    }

    private function queryAccessLevels(): ?array
    {
        $query = "SELECT `id`, `access_level`, `order_level` 
                  FROM access_levels 
                  ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = (array) $stmt->fetchAll();

        if ($result) {
            $this->result = true;
            return $result;
        } else {
            return [];
        }
    }
}