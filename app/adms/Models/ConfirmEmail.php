<?php

namespace App\adms\Models;

use App\adms\Enum\UserSituation;
use App\adms\Models\helpers\Connection;

class ConfirmEmail
{
    private bool $result;
    private int $confirmed = UserSituation::CONFIRMED_EMAIL->value;
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function confirm(string $key): void
    {
        if(! empty($key)) {

            $this->conn = Connection::connect();
            $resultToHash = $this->queryHashConfirmEmail($key);

            if($resultToHash) {
                $this->updateSituation($resultToHash);
                $_SESSION['msg'] = "<div class='alert alert-success'>Email confirmado com sucesso!</div>";
                $this->result = true;
            }
            else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Link inválido!</div>";
                $this->result = false;
            }
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Link inválido!</div>";
            $this->result = false;
        }
    }

    private function queryHashConfirmEmail(string $key): int
    {
        $sql =  "SELECT `id`
                FROM `users` 
                WHERE `confirm_email` = :hash_confirm_email 
                LIMIT 1";

        $resultToHash = $this->conn->prepare($sql);
        $resultToHash->bindValue(':hash_confirm_email', $key, \PDO::PARAM_STR);
        $resultToHash->execute();
        return (int) $resultToHash->fetchColumn();
    }

    private function updateSituation(int $id): bool
    {
        $confirmEmail = null;
        $update = "UPDATE `users` 
                   SET `confirm_email` = :confirm_email,
                       `user_situation_id` = :user_situation_id, 
                       `updated_at` = NOW()
                   WHERE `id` = :id";

        $updateSituation = $this->conn->prepare($update);
        $updateSituation->bindValue(':confirm_email', $confirmEmail);
        $updateSituation->bindValue(':user_situation_id', $this->confirmed, \PDO::PARAM_INT);
        $updateSituation->bindValue(':id', $id, \PDO::PARAM_INT);
        return $updateSituation->execute();
    }
}