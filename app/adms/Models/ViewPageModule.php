<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;

class ViewPageModule
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function viewInfoModule(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsPageModule($id);  
    }

    private function detailsPageModule(int $id): ?array
    {
        $groupDetails = "SELECT 
                        `id`,
                        UPPER(`type`) AS `type`,
                        `name`,
                        `order_module`,
                        `obs`, 
                        `created_at`, 
                        `updated_at`
                      FROM `page_modules`
                      WHERE `id` = :id
                      LIMIT 1";
                      
        $stmt = $this->conn->prepare($groupDetails);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dataResult = (array) $stmt->fetch(PDO::FETCH_ASSOC);
  
        if(! empty($dataResult)) {
            $this->result = true;
            return $dataResult;
        }

        $_SESSION['msg'] = "<div class='alert alert-danger'>Módulo de página não encontrado!</div>";
        $this->result = false;
        return [];
    }
}