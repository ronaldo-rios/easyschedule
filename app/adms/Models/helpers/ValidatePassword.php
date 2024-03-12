<?php

namespace App\adms\Models\helpers;

class ValidatePassword
{
    private static bool $result = false;
    private const MIN_LENGTH = 6;

    public static function getResult(): bool
    {
        return self::$result;
    }

    /**
     * Static function to validate password
     * @param string $password
     * @return void
     */
    public static function validate(string $password): void
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/';

        if (strlen($password) < self::MIN_LENGTH) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>A senha deve ter no mínimo 6 caracteres!</div>";
            self::$result = false;
        } 
        else if (! preg_match($regex, $password)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número!</div>";
            self::$result = false;
        } 
        else if (stristr($password, "'")) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Caractere ( ' ) não permitido!</div>";
            self::$result = false;
        }
        else {
            self::$result = true;
        }
    }
}