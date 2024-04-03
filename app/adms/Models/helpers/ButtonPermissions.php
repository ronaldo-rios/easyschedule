<?php

namespace App\adms\Models\helpers;

use App\adms\Enum\Permission;
use PDO;
use App\adms\Models\helpers\Connection;

class ButtonPermissions
{
    private static ?array $result = [];

    public static function validateButtonPermission(?array $data): ?array
    {
        foreach($data as $key => $button) {
            $resultButtonPermissions = self::query(
                (string) $button['menu_controller'], (string) $button['menu_method']
            );

            !empty($resultButtonPermissions) 
                ? self::$result[$key] = true 
                : self::$result[$key] = false;
        }

        return self::$result;
    }

    private static function query(?string $menuController, ?string $menuMethod): array
    {
        $query = "SELECT p.id 
                    FROM `pages` AS p
                        INNER JOIN `page_levels` AS pl 
                            ON pl.page_id = p.id
                        WHERE p.controller_in_the_main = :controller_in_the_main
                            AND p.method_in_the_main = :method_in_the_main
                            AND pl.permission = :have_permission
                            AND pl.access_level_id = :access_level_id
                        LIMIT 1";

        $conn = Connection::connect();
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':controller_in_the_main', $menuController, PDO::PARAM_STR);
        $stmt->bindValue(':method_in_the_main', $menuMethod, PDO::PARAM_STR);
        $stmt->bindValue(':have_permission', Permission::HAVE_PERMISSION->value, PDO::PARAM_INT);
        $stmt->bindValue(':access_level_id', (int) $_SESSION['access_level'], PDO::PARAM_INT);
        $stmt->execute();
        $queryResult = (array) $stmt->fetch(PDO::FETCH_ASSOC);
        return $queryResult;
    }
}