<?php

namespace App\adms\Models;

use PDO;
use App\adms\Enum\UserSituation;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\Pagination;

class ListUsers
{
    private bool $result = false;
    private object $conn;
    private const LIMIT = 10;
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
        $pagination = new Pagination(URL . 'users/index');
        $pagination->condiction($page, self::LIMIT);
        $countUsers = $this->countUsers();
        $pagination->paginate($countUsers);
        $resultPage = $pagination->getResult();
        $this->dataPagination = $resultPage;

        $this->conn = Connection::connect();
        $users = $this->queryAllUsers();
        $stmt = $this->conn->prepare($users);
        $stmt->bindValue(':confirmed', UserSituation::CONFIRMED_EMAIL->value, PDO::PARAM_INT);
        $stmt->bindValue(':pending', UserSituation::WAITING_FOR_CONFIRMATION->value, PDO::PARAM_INT);
        $stmt->bindValue(':order_level', $_SESSION['order_level'], PDO::PARAM_INT);
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

    private function queryAllUsers(): string
    {
        return "SELECT 
                    user.id, user.name, user.email, user.user
                FROM `users` AS user
                INNER JOIN `access_levels` AS access
                    ON user.access_level_id = access.id
                WHERE `user_situation_id` in (:confirmed, :pending)
                    AND access.order_level > :order_level
                ORDER BY user.id ASC
                LIMIT :limit OFFSET :offset";
    }

    private function countUsers(): int
    {
        $sql = "SELECT COUNT(u.id) AS num_result 
                    FROM `users` AS u
                    INNER JOIN `access_levels` AS access
                        ON u.access_level_id = access.id
                    WHERE u.user_situation_id IN (:confirmed, :pending)
                    AND access.order_level > :order_level";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':confirmed', UserSituation::CONFIRMED_EMAIL->value, PDO::PARAM_INT);
        $stmt->bindValue(':pending', UserSituation::WAITING_FOR_CONFIRMATION->value, PDO::PARAM_INT);
        $stmt->bindValue(':order_level', $_SESSION['order_level'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = (int) $stmt->fetch(PDO::FETCH_ASSOC)['num_result'];
            return $result;
        }

        return 0;
    }
}
