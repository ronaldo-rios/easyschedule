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
        $query = "SELECT u.name, u.email, u.user,
                         u.image, u.image, u.nickname, al.access_level 
                  FROM `users` AS u
                  INNER JOIN `access_levels` AS al
                    ON u.access_level_id = al.id
                  WHERE u.id = :id 
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