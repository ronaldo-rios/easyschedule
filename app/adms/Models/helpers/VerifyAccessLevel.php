<?php

namespace App\adms\Models\helpers;

use PDO;
use App\adms\Models\helpers\Connection;

class verifyAccessLevel
{
    private static object $conn;

    /**
     * Verify if the access level is higher than the user's access level. 
     * If true, the user can access the page.
     * @param int $accessLevelId
     * @return bool
     */
    public static function verifyAccessLevel(int $accessLevelId): array
    {
        $sql = "SELECT `id`, `access_level`
                FROM `access_levels`
                WHERE id = :id 
                AND `order_level` > :order_level
                LIMIT 1";

        self::$conn = Connection::connect();
        $stmt = self::$conn->prepare($sql);
        $stmt->bindValue(':id', $accessLevelId, PDO::PARAM_INT);
        $stmt->bindValue(':order_level', (int) $_SESSION['order_level'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return [];
    }
}