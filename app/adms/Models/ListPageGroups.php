<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\Pagination;

class ListPageGroups
{
    private bool $result = false;
    private object $conn;
    private const LIMIT = 5;
    private ?string $dataPagination;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getPagination(): ?string
    {
        return $this->dataPagination;
    }

    public function list(?int $page): ?array
    {
        $pagination = new Pagination(URL . 'page-groups/index');
        $pagination->condiction($page, self::LIMIT);
        $countUsers = $this->countGroups();
        $pagination->paginate($countUsers);
        $resultPage = $pagination->getResult();
        $this->dataPagination = $resultPage;

        $this->conn = Connection::connect();
        $users = $this->queryAllGroups();
        $stmt = $this->conn->prepare($users);
        $stmt->bindValue(':limit', self::LIMIT, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $pagination->getOffset(), PDO::PARAM_INT);

        $stmt->execute();
        $dataResult = (array) $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(! empty($dataResult)) {
            $this->result = true;
            return $dataResult;
        }

        $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum usuário encontrado!</div>";
        $this->result = false;
        return [];
    }

    private function queryAllGroups(): string
    {
        return "SELECT `id`, `group_name`, `order_page_group`
                FROM `page_groups`
                    ORDER BY `order_page_group`
                    LIMIT :limit OFFSET :offset";
    }

    private function countGroups(): int
    {
        $sql = "SELECT COUNT(`id`) AS num_result 
                    FROM `page_groups`";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = (int) $stmt->fetch(PDO::FETCH_ASSOC)['num_result'];
            return $result;
        }

        return 0;
    }
}