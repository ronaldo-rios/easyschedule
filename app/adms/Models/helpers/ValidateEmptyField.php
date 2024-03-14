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

    /**
     * Static function to validate empty field
     * @param array|null $data
     * @return void
     */
    public static function validateField(?array $data, ?array $ignoreFields = []): void
    {
        self::$data = $data;
        self::$data = array_map('strip_tags', self::$data);
        self::$data = array_map('trim', self::$data);
        // filters fields that should be ignored and should not be validated:
        $filteredData = array_diff_key(self::$data, array_flip($ignoreFields));

        if (in_array('', $filteredData)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Necessário preencher todos os campos!</div>";
            self::$result = false;
        } else {
            self::$result = true;
        }
    }
}