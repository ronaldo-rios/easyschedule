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
    private const LIMIT = 10;

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
                        ps.status
                    FROM `pages` AS pg
                      INNER JOIN `page_modules` AS pm
                        ON pg.page_module_id = pm.id
                      INNER JOIN `page_status` AS ps
                        ON pg.page_status_id = ps.id
                      INNER JOIN `page_levels` AS pl
                        ON pg.id = pl.page_id
                        AND pl.permission = 1
                      INNER JOIN `access_levels` AS al
                        ON pl.access_level_id = al.id
                        AND al.order_level >= :order_level
                    GROUP BY 
                        pg.id,
                        pg.name_page,
                        pm.type,
                        pm.name,
                        ps.status
                    ORDER BY `name_page` ASC
                    LIMIT :limit OFFSET :offset";
                

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_level', $_SESSION['access_level'], PDO::PARAM_INT);
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
        $query = "SELECT COUNT(p.id) AS count 
                    FROM `pages` AS p
                    INNER JOIN `page_levels` AS pl
                            ON p.id = pl.page_id
                            AND pl.permission = 1
                            AND pl.access_level_id = :access_level
                        INNER JOIN `access_levels` AS al
                            ON pl.access_level_id = al.id
                            AND al.order_level >= :order_level
                            WHERE page_status_id = 1";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_level', $_SESSION['access_level'], PDO::PARAM_INT);
        $stmt->bindValue(':access_level', $_SESSION['access_level'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }
}