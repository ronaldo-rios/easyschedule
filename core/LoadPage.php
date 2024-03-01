<?php

namespace Core;

use App\adms\Models\helpers\SlugControllerOrMethod;

class LoadPage
{
    private static string $urlController;
    private static string $urlMethod;
    private static string $urlParameter;
    private static string $classLoad;

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

        self::$classLoad = "\\App\\adms\\Controllers\\" . self::$urlController;

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
            $classLoad->{self::$urlMethod}();
        } 
        else {
            self::$urlMethod = SlugControllerOrMethod::slugMethod(METHOD);
            self::$urlParameter = "";
            self::load(self::$urlController, self::$urlMethod, self::$urlParameter);
        }
    }
}