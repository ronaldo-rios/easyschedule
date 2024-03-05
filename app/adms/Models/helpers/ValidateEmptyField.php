<?php

namespace App\adms\Models\helpers;

class ValidateEmptyField
{
    private static ?array $data;
    private static bool $result;

    public static function getResult(): bool
    {
        return self::$result;
    }

    public static function validateField(?array $data): void
    {
        self::$data = $data;
        self::$data = array_map('strip_tags', self::$data);
        self::$data = array_map('trim', self::$data);
        
        if (in_array('', self::$data)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos!</div>";
            self::$result = false;
        } else {
            self::$result = true;
        }
    }
}