<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class AddPageGroup
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

        $this->insertNewPageGroup($data);
    }

    private function queryToLastOrderPageGroup(): ?int
    {
        $select = "SELECT `order_page_group` 
                   FROM `page_groups`
                   ORDER BY `order_page_group` DESC 
                   LIMIT 1";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($select);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    private function insertNewPageGroup(array $data): void
    {
        $orderGroup = $this->queryToLastOrderPageGroup();
        $orderGroup += 1;

        $insert = "INSERT INTO `page_groups` (`group_name`, `order_page_group`, `created_at`) 
                   VALUES (:group_name, :order_page_group, NOW())";

        $stmt = $this->conn->prepare($insert);
        $stmt->bindValue(
            ':group_name', 
            ConvertToCapitularString::format($data['group_name']), 
            \PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':order_page_group', 
            $orderGroup, 
            \PDO::PARAM_INT
        );
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<span style='color: green;'>Grupo cadastrado com sucesso!</span><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro na criação de Grupo!</div><br>";
            $this->result = false;
        }
    }
}