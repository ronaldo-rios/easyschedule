<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;

class ViewPageGroup
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function viewInfoGroup(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsPageGroup($id);  
    }

    private function detailsPageGroup(int $id): ?array
    {
        $groupDetails = "SELECT 
                        `id`,
                        `group_name`, 
                        `order_page_group`, 
                        `created_at`, 
                        `updated_at`
                      FROM `page_groups`
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

        $_SESSION['msg'] = "<div class='alert alert-danger'>Grupo de página não encontrado!</div>";
        $this->result = false;
        return [];
    }
}