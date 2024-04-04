<?php

namespace Core;

use App\adms\Enum\Permission;
use App\adms\Models\helpers\Connection;
use App\adms\Models\helpers\SlugControllerOrMethod;

class LoadPageLevels
{
    private static string $urlController;
    private static string $urlMethod;
    private static string $urlParameter;
    private static string $classLoad;
    private static array $pageResult;
    private const PUBLIC_PAGE = 1;
    
    /**
     * This method is responsible for loading and redirecting the user to the requested page or to the default page:
     * @param string|null $urlControler
     * @param string|null $urlMethod
     * @param string|null $urlParameter
     * @return void
     */
    public static function load(?string $urlControler, ?string $urlMethod, ?string $urlParameter): void
    {
        self::$urlController = $urlControler;
        self::$urlMethod = $urlMethod;
        self::$urlParameter = $urlParameter;
        // var_dump(self::$urlController, self::$urlMethod, self::$urlParameter);die;
        self::searchPages();
    }

    private static function searchPages()
    {
        $sql = "SELECT p.id, p.public, m.type 
                FROM `pages` AS p
                    INNER JOIN `page_modules`AS m
                        ON m.id = p.page_module_id
                WHERE p.controller = :controller
                AND p.method = :method
                LIMIT 1";

        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':controller', self::$urlController, \PDO::PARAM_STR);
        $stmt->bindValue(':method', self::$urlMethod, \PDO::PARAM_STR);
        $stmt->execute();
        self::$pageResult = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (self::$pageResult) {
            if ((int) self::$pageResult['public'] === self::PUBLIC_PAGE) {
                self::$classLoad = "App\\" . self::$pageResult['type'] . "\\Controllers\\" . self::$urlController;
                self::loadMethod();
            } else {
                self::verifyLoged();
            }
        }
        else {
            // $_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada!</div>";
            // tratar esse 404 futuramente
            $url = URL . "error/index";
            header("Location: $url");
            exit;
        }
    }

    /**
     * This method is responsible for loading the method requested by the user or the default method:
     * @return void
     */
    private static function loadMethod(): void
    {
        $classLoad = new self::$classLoad();
        if (method_exists($classLoad, self::$urlMethod)) {
            $classLoad->{self::$urlMethod}(self::$urlParameter);
        } 
        else {
            self::$urlMethod = SlugControllerOrMethod::slugMethod(METHOD);
            self::$urlParameter = "";
            self::load(self::$urlController, self::$urlMethod, self::$urlParameter);
        }
    }

    /**
     * Check if the user is logged in:
     * @return void
     */
    private static function verifyLoged(): void
    {
        if (
            isset($_SESSION['user_id']) &&
            isset($_SESSION['user_name']) && 
            isset($_SESSION['user_email']) &&
            isset($_SESSION['access_level']) &&
            isset($_SESSION['order_level'])
        ) {
            self::searchIfUserHasAccessToPage();
        }
        else {
            echo "não entrou";die;
            $_SESSION['msg'] = "<div class='alert alert-danger'>Faça login para acessar.</div>";
            $url = URL . "login/index";
            header("Location: $url");
            exit;
        }
    }

    /**
     * Check if the user has access to the requested page. If the user has access, the method is loaded, 
     * else the user is redirected to the error page:
     * @return void
     */
    private static function searchIfUserHasAccessToPage(): void
    {
        $sql = "SELECT `id`, `permission` 
                FROM `page_levels`
                WHERE `page_id` = :page_id
                    AND `access_level_id` = :access_level_id
                    AND `permission` = :permission";
     
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':page_id', self::$pageResult['id'], \PDO::PARAM_INT);
        $stmt->bindValue(':access_level_id', (int) $_SESSION['access_level'], \PDO::PARAM_INT);
        $stmt->bindValue(':permission', Permission::HAVE_PERMISSION->value, \PDO::PARAM_INT);
        $stmt->execute();
        $resultPageLevel = (array) $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if (! empty($resultPageLevel)) {
            self::$classLoad = "\\App\\". self::$pageResult['type'] ."\\Controllers\\" . self::$urlController;
            self::loadMethod();
        }
        else {
            $url = URL . "error/index";
            header("Location: $url");
            exit;
        }
    }
}