<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\Pagination;

class ListPageModules
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

    public function list(int $page): array
    {
        $pagination = new Pagination(URL . 'page-modules/index');
        $pagination->condiction($page, self::LIMIT);
        $countConfEmails = $this->contPageModules();
        $pagination->paginate($countConfEmails);
        $resultPage = $pagination->getResult();
        $this->dataPagination = $resultPage;
        
        $this->conn = Connection::connect();
        return $this->queryPageModules($pagination);
    }

    private function queryPageModules(Pagination $pagination): array
    {
        $query = "SELECT `id`, UPPER(`type`) AS `type`, `name`, `order_module`
                    FROM `page_modules`
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

    private function contPageModules(): int
    {
        $query = "SELECT COUNT(id) AS count FROM `page_modules`";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }
}