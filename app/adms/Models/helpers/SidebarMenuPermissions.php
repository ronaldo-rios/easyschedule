<?php

namespace App\adms\Models\helpers;

use App\adms\Enum\Permission;
use App\adms\Models\helpers\Connection;

class SidebarMenuPermissions
{
    private const TRUE = 1;
    public static function checkPermissionsSidebarMenus(): array
    {
        return self::querySidebarMenus();
    }

    private static function querySidebarMenus(): array
    {
        $sidebarMenus = "SELECT pl.id AS page_level_id, 
                                p.id AS page_id,
                                p.controller_in_the_main
                            FROM `page_levels`AS pl
                                INNER JOIN `pages` AS p
                                    ON pl.page_id = p.id
                            WHERE pl.access_level_id = :access_level_id
                                AND pl.permission = :permission
                                AND p.enable_in_sidebar = :enable_in_sidebar
                            ORDER BY pl.order_level_page ASC";

        $conn = Connection::connect();
        $statement = $conn->prepare($sidebarMenus);
        $statement->bindValue(':access_level_id', (int) $_SESSION['access_level'], \PDO::PARAM_INT);
        $statement->bindValue(':permission', Permission::HAVE_PERMISSION->value, \PDO::PARAM_INT);
        $statement->bindValue(':enable_in_sidebar', self::TRUE, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}