<?php

namespace App\adms\Models;

use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\ConvertToCapitularString;
use App\adms\Models\helpers\ValidateEmptyField;

class AddPage
{
    private bool $result = false;
    private array $data = [];
    private object $conn;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function add(array $formData): void
    {
        $ignoreOptionalFields = ['icon', 'obs'];
        ValidateEmptyField::validateField($this->data, $ignoreOptionalFields);
        if (! ValidateEmptyField::getResult()) {
            $this->result = false;
            return;
        }

        $this->insertNewPage($formData);
    }

    private function insertNewPage(array $formData): void
    {
        $insert = "INSERT INTO `pages` 
                    (
                      `controller`, `method`, `controller_in_the_main`,
                      `method_in_the_main`, `name_page`, `public`, `icon`, `obs`, 
                      `page_status_id`, `page_group_id`, `page_module_id`, `created_at`
                    )
                   VALUES 
                    (
                        :controller, :method, :controller_in_the_main,
                        :method_in_the_main, :name_page, :public, :icon, :obs, 
                        :page_status_id, :page_group_id, :page_module_id, NOW()
                    )";
        
        $controller = iconv('UTF-8', 'ASCII//TRANSLIT', trim($formData['controller']));
        $obs = !empty($formData['obs']) ? ucfirst(mb_strtolower($formData['obs'])) : null;
        $icon = !empty($formData['icon']) ? $formData['icon'] : null;
        /* icon function to trying to remove special characters and replace 
        them with a compatible ASCII value and force all text to be lowercase */
        $method = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $formData['method']));
        $controllerMain = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', trim($formData['controllermenu'])));
        $methodMain = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', trim($formData['methodmenu'])));

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($insert);
        $stmt->bindValue(':controller', $controller, \PDO::PARAM_STR);
        $stmt->bindValue(':method', $method, \PDO::PARAM_STR);
        $stmt->bindValue(':controller_in_the_main', $controllerMain, \PDO::PARAM_STR);
        $stmt->bindValue(':method_in_the_main', $methodMain, \PDO::PARAM_STR);
        $stmt->bindValue(':name_page', ConvertToCapitularString::format($formData['name']), \PDO::PARAM_STR);
        $stmt->bindValue(':public', intval($formData['public']), \PDO::PARAM_INT);
        $stmt->bindValue(':icon', $icon, \PDO::PARAM_STR);
        $stmt->bindValue(':obs', $obs, \PDO::PARAM_STR);
        $stmt->bindValue(':page_status_id', intval($formData['status']), \PDO::PARAM_INT);
        $stmt->bindValue(':page_group_id', intval($formData['group']), \PDO::PARAM_INT);
        $stmt->bindValue(':page_module_id', intval($formData['module']), \PDO::PARAM_INT);

        $stmt->execute();
        
        if ($stmt->rowCount()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Página cadastrada com sucesso!</div>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar a página!</div>";
            $this->result = false;
        }
    }

    public function listSelectStatus(): ?array
    {
        $this->conn = Connection::connect();
        $sql = "SELECT `id`, `status`
                FROM `page_status`
                ORDER BY `status` ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listSelectPageGroupId(): ?array
    {
        $this->conn = Connection::connect();
        $sql = "SELECT `id`, `group_name` 
                FROM `page_groups`
                ORDER BY `group_name` ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listSelectModuleId(): ?array
    {
        $this->conn = Connection::connect();
        $sql = "SELECT 
                    `id`, 
                    CONCAT(UPPER(`type`), ' - ', `name`) AS module_name
                FROM `page_modules`
                ORDER BY module_name ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}