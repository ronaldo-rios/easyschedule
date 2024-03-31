<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\Pagination;

class ListPages
{
    private object $conn; 
    private bool $result = false;
    private ?string $dataPagination;
    private const LIMIT = 5;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getPagination(): ?string
    {
        return $this->dataPagination;
    }

    public function list(?int $page = null): array
    {
        $pagination = new Pagination(URL . 'pages/index');
        $pagination->condiction($page, self::LIMIT);
        $countConfEmails = $this->countPages();
        $pagination->paginate($countConfEmails);
        $resultPage = $pagination->getResult();
        $this->dataPagination = $resultPage;
        
        $this->conn = Connection::connect();
        return $this->queryPages($pagination);
    }

    private function queryPages(Pagination $pagination): array
    {
        $query = "SELECT 
                        pg.id, 
                        pg.name_page,
                        UPPER(pm.type) AS module_type, 
                        pm.name AS module_name,
                        ps.status,
                        c.color_name
                    FROM `pages` AS pg
                      INNER JOIN `page_modules` AS pm
                        ON pg.page_module_id = pm.id
                      INNER JOIN `page_status` AS ps
                        ON pg.page_status_id = ps.id
                      INNER JOIN `colors` AS c
                        ON ps.color_id = c.id
                   ORDER BY `type`, `name`
                   LIMIT :limit OFFSET :offset";
                

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', self::LIMIT, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $pagination->getOffset(), PDO::PARAM_INT);
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

    private function countPages(): int
    {
        $query = "SELECT COUNT(id) AS count FROM `pages`";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }
}