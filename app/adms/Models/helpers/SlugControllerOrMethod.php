<?php

namespace App\adms\Models\helpers;

class SlugControllerOrMethod
{
    private static string $urlSlugController;
    private static string $urlSlugMethod;

    /**
     * This method is responsible for converting the URL slug into a controller name:
     * @param string $slugController
     * @return string
     */
    public static function slugController(string $slugController): string
    {
        self::$urlSlugController = $slugController;
        self::$urlSlugController = strtolower(self::$urlSlugController);
        self::$urlSlugController = str_replace('-', ' ', self::$urlSlugController);
        self::$urlSlugController = ucwords(self::$urlSlugController);
        self::$urlSlugController = str_replace(' ', '', self::$urlSlugController);

        return self::$urlSlugController;
    }

    /**
     * This method is responsible for converting the URL slug into a method name:
     * @param string $urlMethod
     * @return string
     */
    public static function slugMethod(string $urlMethod): string
    {
        self::$urlSlugMethod = $urlMethod;
        self::$urlSlugMethod = strtolower(self::$urlSlugMethod);
        self::$urlSlugMethod = str_replace('-', ' ', self::$urlSlugMethod);
        self::$urlSlugMethod = ucwords(self::$urlSlugMethod);
        self::$urlSlugMethod = str_replace(' ', '', self::$urlSlugMethod);
        self::$urlSlugMethod = lcfirst(self::$urlSlugMethod);

        return self::$urlSlugMethod;
    }
}