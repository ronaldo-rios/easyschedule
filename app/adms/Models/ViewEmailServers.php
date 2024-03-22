<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;

class ViewEmailServers
{
    private object $conn; 
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getEmails(): array
    {
        $this->conn = Connection::connect();
        return $this->emailServers();
    }

    private function emailServers(): array
    {
        $query = "SELECT * FROM `config_emails` ORDER BY `id`";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultEmails = (array) $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if ($resultEmails) {
            $this->result = true;
            return $resultEmails;
        }
        else {
            return [];
        }
    }
}