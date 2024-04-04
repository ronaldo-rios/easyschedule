<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\Pagination;
use PDO;

class EmailServers
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

    public function getEmails(int $page): array
    {
        $pagination = new Pagination(URL . 'email-servers/index');
        $pagination->condiction($page, self::LIMIT);
        $countConfEmails = $this->contEmailServers();
        $pagination->paginate($countConfEmails);
        $resultPage = $pagination->getResult();
        $this->dataPagination = $resultPage;
        
        $this->conn = Connection::connect();
        return $this->emailServers($pagination);
    }

    private function emailServers(Pagination $pagination): array
    {
        $query = "SELECT * FROM `config_emails` 
                  ORDER BY `id`
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

    private function contEmailServers(): int
    {
        $query = "SELECT COUNT(id) AS count FROM `config_emails`";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
    }
}