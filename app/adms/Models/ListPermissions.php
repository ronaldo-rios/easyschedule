<?php

namespace App\adms\Models;

use PDO;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\Pagination;
use App\adms\Models\helpers\VerifyAccessLevel;

class ListPermissions
{
    private bool $result = false;
    private object $conn;
    private ?string $dataPagination;
    private const LIMIT = 10;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getPagination(): ?string
    {
        return $this->dataPagination;
    }

    public function listPermissions(?int $accessLevelId = null, ?int $page = null): ?array
    {
        $verifiedTrue = VerifyAccessLevel::verifyAccessLevel($accessLevelId);
        if ($verifiedTrue){
            $pagination = new Pagination(URL . 'permissions/index', '?level=' . $accessLevelId);
            $pagination->condiction($page, self::LIMIT);
            $countConfEmails = $this->countPermissions($accessLevelId);
            $pagination->paginate($countConfEmails);
            $resultPage = $pagination->getResult();
            $this->dataPagination = $resultPage;
            
            $this->conn = Connection::connect();
            return $this->queryPermissions($pagination, $accessLevelId);
        }
        
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nível de acesso não encontrado!</div>";
        return [];
    }

    private function queryPermissions(Pagination $pagination, int $accessLevelId): array
    {
        $permissions = "SELECT 
                            pl.id, 
                            CASE pl.permission
                                WHEN 1 THEN 'Liberado'
                                ELSE 'Bloqueado'
                            END AS permission,
                            pl.order_level_page, 
                            pl.page_id,
                            p.name_page, 
                            pl.access_level_id,
                            al.access_level
                        FROM `page_levels` AS pl
                            INNER JOIN `pages` AS p
                                ON pl.page_id = p.id
                            INNER JOIN `access_levels` AS al
                                ON pl.access_level_id = al.id
                        WHERE pl.access_level_id = :access_level_id
                        ORDER BY pl.order_level_page ASC
                        LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($permissions);
        $stmt->bindValue(':access_level_id', $accessLevelId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', self::LIMIT, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $pagination->getOffset(), PDO::PARAM_INT);
        $stmt->execute();

        $dataResult = (array) $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (! empty($dataResult)) {
            $this->result = true;
            return $dataResult;
        }

        $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhuma permissão encontrada!</div>";
        $this->result = false;
        return [];
    }

    private function countPermissions(int $accessLevel): int
    {
        $sql = "SELECT COUNT(id) AS num_result
                FROM `page_levels`
                WHERE access_level_id = :access_level_id";

        $this->conn = Connection::connect();
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':access_level_id', $accessLevel, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = (int) $stmt->fetch(PDO::FETCH_ASSOC)['num_result'];
            return $result;
        }

        return 0;
    }
}