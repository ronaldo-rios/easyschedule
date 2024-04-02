<?php

namespace App\adms\Controllers;

use App\adms\Models\EditPermission as ModelsEditPermission;

class EditPermission
{
    public function index(int|string|null $id): void
    {
        $permissionId = (int) $id;
        $level = (int) filter_input(INPUT_GET, 'level', FILTER_SANITIZE_NUMBER_INT);
        $page = (int) filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($permissionId) && !empty($level) && !empty($page)) {
            $this->editPermission($permissionId);
            header("Location: " . URL . "permissions/index/{$page}?level={$level}");exit;
        } 
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Permissão não encontrada!</div>";
            header("Location: " . URL . "permissions/index");exit;
        }
    }

    private function editPermission(int $permissionId): void
    {
        $updatePermission = new ModelsEditPermission();
        $updatePermission->edit($permissionId);
    }
}