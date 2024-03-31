<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ValidateEmptyField;
use App\adms\Models\helpers\ConvertToCapitularString;

class AddPageModule
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
        ValidateEmptyField::validateField($this->data, ['obs']);
        if (! ValidateEmptyField::getResult()) {
            $this->result = false;
            return;
        }

        $this->insertNewPageModule($data);
    }

    private function queryToLastOrderPageModule(): ?int
    {
        $select = "SELECT `order_module` 
                   FROM `page_modules`
                   ORDER BY `order_module` DESC
                   LIMIT 1";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($select);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    private function insertNewPageModule(array $data): void
    {
        $orderModule = $this->queryToLastOrderPageModule();
        $orderModule += 1;

        $insert = "INSERT INTO `page_modules` 
                    (`type`, `name`, `order_module`, `obs`, `created_at`) 
                   VALUES (:type, :name, :order_module, :obs, NOW())";

        $stmt = $this->conn->prepare($insert);
        $stmt->bindValue(':type', mb_strtolower($data['type'], 'UTF-8'), \PDO::PARAM_STR);
        $stmt->bindValue(':name', ConvertToCapitularString::format($data['name']), \PDO::PARAM_STR);
        $stmt->bindValue(':order_module', $orderModule, \PDO::PARAM_INT);
        $stmt->bindValue(':obs', ucfirst(mb_strtolower($data['obs'], 'UTF-8')), \PDO::PARAM_STR); 
        $stmt->execute();

        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<span style='color: green;'>Módulo cadastrado com sucesso!</span><br>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro na criação de Módulo!</div><br>";
            $this->result = false;
        }
    }
}