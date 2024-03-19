<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

class ViewProfile
{
    private object $conn;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function get(): ?array
    {
        $this->conn = Connection::connect();
        return $this->getInfoUserLoged();
    }

    private function getInfoUserLoged(): ?array
    {   
        $query = "SELECT `name`, `email`, `user`,
                         `image`, `nickname`
                  FROM `users` 
                  WHERE `id` = :id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $_SESSION['user_id'], \PDO::PARAM_INT);
        $stmt->execute();
        $result = (array) $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($result) {
            $this->result = true;
            return $result;
        } 
        else {
            $this->result = false;
            return [];
        }
    }
}