<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;

class ViewPage
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function viewInfoPage(int $id): ?array
    {
        $this->conn = Connection::connect();
        return $this->detailsPage($id);  
    }

    private function detailsPage(int $id): ?array
    {
        $groupDetails = "SELECT 
                            p.id,
                            p.name_page,
                            p.public,
                            p.obs,
                            p.created_at,
                            p.updated_at,
                            pg.group_name,
                            UPPER(pm.type) AS type,
                            pm.name,
                            ps.status,
                            c.color
                      FROM `pages` AS p
                        INNER JOIN `page_groups` AS pg
                            ON p.page_group_id = pg.id
                        INNER JOIN `page_modules` AS pm
                            ON p.page_module_id = pm.id
                        INNER JOIN `page_status` AS ps
                            ON p.page_status_id = ps.id
                        INNER JOIN `colors` AS c
                            ON ps.color_id = c.id
                      WHERE p.id = :id
                      LIMIT 1";
                      
        $stmt = $this->conn->prepare($groupDetails);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dataResult = (array) $stmt->fetch(PDO::FETCH_ASSOC);
  
        if(! empty($dataResult)) {
            $this->result = true;
            return $dataResult;
        }

        $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada!</div>";
        $this->result = false;
        return [];
    }
}