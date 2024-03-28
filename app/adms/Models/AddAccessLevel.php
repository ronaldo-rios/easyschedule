<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class AddAccessLevel
{
    private bool $result = false;
    private array $data = [];
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function add(?array $data): void
    {
        ValidateEmptyField::validateField($this->data);
        if (! ValidateEmptyField::getResult()) {
            $this->result = false;
            return;
        }

        $this->insertAccessLevel($data);
    }

    private function queryToLastOrder(): ?int
    {
        $select = "SELECT `order_level` 
                   FROM `access_levels` 
                   ORDER BY `order_level` DESC 
                   LIMIT 1";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($select);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    private function insertAccessLevel(array $data): void
    {
        $orderLevel = $this->queryToLastOrder();
        $orderLevel += 1;

        $insert = "INSERT INTO `access_levels` (`access_level`, `order_level`, `created_at`) 
                   VALUES (:access_level, :order_level, NOW())";

        $stmt = $this->conn->prepare($insert);
        $stmt->bindValue(':access_level', ConvertToCapitularString::format($data['name']), \PDO::PARAM_STR);
        $stmt->bindValue(':order_level', $orderLevel, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<span style='color: green;'>Nível de acesso cadastrado com sucesso!</span><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro na criação de nível de acesso!</div><br>";
            $this->result = false;
        }
    }

}