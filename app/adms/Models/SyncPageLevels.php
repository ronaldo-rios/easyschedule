<?php

namespace App\adms\Models;

use App\adms\Enum\AccessLevels;
use App\adms\Models\helpers\Connection;

class SyncPageLevels
{
    private object $conn;
    private bool $result = false;
    private ?array $dataLevelPage;
    private const HAVE_PERMISSION = 1;
    private const NO_PERMISSION = 0;
    private const PUBLIC_PAGE = 1;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function sync(): void
    {
        $this->conn = Connection::connect();
        $this->queryDataAccessLevels();
    }

    private function queryDataAccessLevels(): void
    {
        $sql = "SELECT `id` FROM `access_levels`";

        $statement = $this->conn->prepare($sql);
        $statement->execute();
        $accessLevels = (array) $statement->fetchAll();

        if (!empty($accessLevels)) {
            $this->result = true;
            $this->queryDataPages($accessLevels);
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum nível de acesso encontrado!</div>";
            $this->result = false;
        }
    }

    private function queryDataPages(?array $accessLevels): void
    {
        $sql = "SELECT `id`, `public` FROM `pages`";

        $statement = $this->conn->prepare($sql);
        $statement->execute();
        $pages = (array) $statement->fetchAll();

        if (!empty($pages)) {
            $this->result = true;
            $this->readAccesLevels($accessLevels, $pages);
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhuma página encontrada!</div>";
            $this->result = false;
        }
    }

    private function readAccesLevels(?array $resultLevels, ?array $pages): void
    {
        foreach($resultLevels as $level) {
            $level['id']; 
            $this->readPages($pages, (int) $level['id']);
        }
    }

    private function readPages(?array $resultPages, ?int $accessLevelId): void
    {
        foreach($resultPages as $page) {
            $page['id'];
            $page['public'];
            $this->searchListPageLevel($page, $accessLevelId);
        }
    }

    /**
     * Searches for page levels to check whether a given access level already 
     * has registration for a given page in page_levels, 
     * so it doesn't add it to avoid creating duplication
     * If you don't already have it, it synchronizes to make the addition
     */
    private function searchListPageLevel(?array $page, ?int $accessLevelId): void
    {
        $sql = "SELECT `id` FROM `page_levels` 
                WHERE `access_level_id` = :access_level_id
                AND `page_id` = :page_id";

        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':access_level_id', $accessLevelId, \PDO::PARAM_INT);
        $statement->bindValue(':page_id', (int) $page['id'], \PDO::PARAM_INT);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $_SESSION['msg'] = "<p class='alert-success'>Todas as permissões estão sincronizadas!</p>";
            $this->result = true;
        }
        else {
            $this->addLevelPermission($accessLevelId, (int) $page['public'], (int) $page['id']);
        }
    }

    private function queryLastOrderLevelPage(int $accessLevelId): int
    {
        $sql = "SELECT `order_level_page`, `access_level_id`
                FROM `page_levels` 
                WHERE `access_level_id` = :access_level_id
                ORDER BY `order_level_page` DESC 
                LIMIT 1";

        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':access_level_id', $accessLevelId, \PDO::PARAM_INT);
        $statement->execute();
        $lastOrder = (int) $statement->fetchColumn();

        return $lastOrder ? $lastOrder : 0;
    }

    private function addLevelPermission(int $accessLevelId, int $public, int $pageId): void
    {
        $lastOrderResult = $this->queryLastOrderLevelPage($accessLevelId);
        // As soon as a new page is registered, it is automatically released to the master user
        $permission = ($accessLevelId == AccessLevels::MASTER->value) || ($public == self::PUBLIC_PAGE)
            ? self::HAVE_PERMISSION : self::NO_PERMISSION;
        $orderLevelPage = $lastOrderResult + 1;
        $accessLevelId = $accessLevelId;
        $pageId = $pageId;
        $this->insertLevelPage($permission, $orderLevelPage, $accessLevelId, $pageId);
    }

    private function insertLevelPage(int $permission, int $orderLevelPage, int $accessLevelId, int $pageId)
    {
        $sql = "INSERT INTO `page_levels` 
                (`permission`, `order_level_page`, `access_level_id`, `page_id`, `created_at`) 
                VALUES 
                (:permission, :order_level_page, :access_level_id, :page_id, NOW())";

        $statement = $this->conn->prepare($sql);
        $statement->bindValue(':permission', $permission, \PDO::PARAM_INT);
        $statement->bindValue(':order_level_page', $orderLevelPage, \PDO::PARAM_INT);
        $statement->bindValue(':access_level_id', $accessLevelId, \PDO::PARAM_INT);
        $statement->bindValue(':page_id', $pageId, \PDO::PARAM_INT);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $_SESSION['msg'] = "<div class='alert alert-success' role='alert'>Permissões sincronizadas com sucesso!</div>";
            $this->result = true;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao sincronizar permissões!</div>";
            $this->result = false;
        }
    }
}