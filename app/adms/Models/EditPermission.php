<?php

namespace App\adms\Models;

use App\adms\Enum\Permission;
use App\adms\Models\helpers\Connection;

class EditPermission
{
    private object $conn;
    private bool $result = false;
    
    public function edit(int $id): void
    {
        $this->conn = Connection::connect();
        $resultQuery = $this->queryInfoPageLevels($id);

        if (! empty($resultQuery)) {
            $permissionPageLevel = (int) $resultQuery['permission'];
            $pageLevelId = (int) $resultQuery['id'];

            if ($permissionPageLevel === Permission::HAVE_PERMISSION->value) {
                $permissionPageLevel = Permission::NO_PERMISSION->value;
            } else {
                $permissionPageLevel = Permission::HAVE_PERMISSION->value;
            }
            
            $this->updatePermission($permissionPageLevel, $pageLevelId);
        }
        
    }

    private function queryInfoPageLevels(int $id): array
    {
        $query = "SELECT pl.id, pl.permission
                  FROM `page_levels` AS pl
                    INNER JOIN `access_levels` AS level
                        ON pl.access_level_id = level.id
                  WHERE pl.id = :id
                    AND level.order_level > :order_level
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->bindValue(':order_level', (int) $_SESSION['order_level'], \PDO::PARAM_INT);
        $stmt->execute();
        $infoPageLevels = (array) $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $this->result = true;
            return $infoPageLevels;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário selecionar uma página válida!</div>";
            $this->result = false;
            return [];
        }
    }

    private function updatePermission(int $permission, int $pageLevelsId): void
    {
        $update = "UPDATE `page_levels`
                   SET permission = :permission, updated_at = NOW()
                   WHERE id = :id";

        $stmt = $this->conn->prepare($update);
        $stmt->bindValue(':permission', $permission, \PDO::PARAM_INT);
        $stmt->bindValue(':id', $pageLevelsId, \PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $this->result = true;
            $_SESSION['msg'] = "<div class='alert alert-success'>Permissão alterada com sucesso!</div>";
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao alterar permissão!</div>";
            $this->result = false;
        }
    }
}