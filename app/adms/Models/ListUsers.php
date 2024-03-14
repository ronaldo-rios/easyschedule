<?php

namespace App\adms\Models;

use App\adms\Enum\UserSituation;
use App\adms\Models\helpers\Connection;
use PDO;

class ListUsers
{
    private bool $result = false;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function list(): ?array
    {
        $this->conn = Connection::connect();
        $users = $this->queryAllUsers();
        $stmt = $this->conn->prepare($users);
        $stmt->bindValue(':confirmed', UserSituation::CONFIRMED_EMAIL->value, PDO::PARAM_INT);
        $stmt->bindValue(':pending', UserSituation::WAITING_FOR_CONFIRMATION->value, PDO::PARAM_INT);
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
        return "SELECT `id`, `name`, `email`, `user` 
                FROM users 
                WHERE `user_situation_id` in (:confirmed, :pending)
                ORDER BY `name` ASC";
    }
}
