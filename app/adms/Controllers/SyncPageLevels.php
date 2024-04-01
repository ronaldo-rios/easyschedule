<?php

namespace App\adms\Controllers;

use App\adms\Models\SyncPageLevels as ModelsSyncPageLevels;

class SyncPageLevels
{
    public function index(): void
    {
        $syncPageLevels = new ModelsSyncPageLevels();
        $syncPageLevels->sync();
        header("Location: " . URL . "access-levels/index"); exit;
    }
}