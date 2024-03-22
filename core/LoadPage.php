<?php

namespace Core;

use App\adms\Models\helpers\SlugControllerOrMethod;

class LoadPage
{
    private static string $urlController;
    private static string $urlMethod;
    private static string $urlParameter;
    private static string $classLoad;
    private static array $listPublicPage;
    private static array $listPrivatePage;

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

        self::publicPage();

        if(class_exists(self::$classLoad)) {
            self::loadMethod();
        }
        else {
            self::$urlController = SlugControllerOrMethod::slugController(CONTROLLER);
            self::$urlMethod = SlugControllerOrMethod::slugMethod(METHOD);
            self::$urlParameter = "";
            self::load(self::$urlController, self::$urlMethod, self::$urlParameter);
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
     * Check if the requested page is public else check if the user is logged in:
     * @return void
     */
    private static function publicPage(): void
    {
        // List of public pages:
        self::$listPublicPage = [
            "Login", 
            "Error", 
            "Logout", 
            "NewUser", 
            "ConfirmEmail", 
            "NewConfirmEmail", 
            "RecoverPassword",
            "UpdatePassword"
        ];

        in_array(self::$urlController, self::$listPublicPage)
            ? self::$classLoad = "\\App\\adms\\Controllers\\" . self::$urlController
            : self::privatePage();
    }

    /**
     * Check if the user is logged in else redirect to the error page:
     * @return void
     */
    private static function privatePage(): void
    {
        // List of private pages:
        self::$listPrivatePage = [
            "Dashboard", 
            "Users", 
            "ViewUser", 
            "EditUser", 
            "AddUser", 
            "DeleteUser",
            "ViewProfile",
            "EditProfile",
            "ViewEmailServers",
            "EditEmailServer",
            "AddEmailServer"
        ];

        if(in_array(self::$urlController, self::$listPrivatePage)) {
            self::verifyLoged();
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
     * Check if the user is logged in:
     * @return void
     */
    private static function verifyLoged(): void
    {
        if (
            isset($_SESSION['user_id']) &&
            isset($_SESSION['user_name']) && 
            isset($_SESSION['user_email'])
        ) {
            self::$classLoad = "\\App\\adms\\Controllers\\" . self::$urlController;
        }
        else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Faça login para acessar.</div>";
            $url = URL . "login/index";
            header("Location: $url");
            exit;
        }
    }
}