<?php

namespace App\adms\Models;

use App\adms\Models\helpers\SendEmail;
use App\adms\Models\helpers\Connection;

class AdmsEmailCredencials
{
    private static bool $result = false;
    private static int $optionConfigEmail = 1;

    public static function getResult(): bool
    {
        return self::$result;
    }

    public static function readEmailCredencials(array $emailData): void
    {
        $conn = Connection::connect();
        $readEmailCredencials = $conn->prepare(self::queryCredentials());
        $readEmailCredencials->bindValue(':id', self::$optionConfigEmail, \PDO::PARAM_INT);
        $readEmailCredencials->execute();
        $resultDb = (array) $readEmailCredencials->fetch();

        if ($resultDb) {
            $sendEmail = new SendEmail();
            $sendEmail->send($resultDb, $emailData);
            self::$result = true;
        }
        else {
            self::$result = false;
        }
    }

    private static function queryCredentials(): string
    {
        return "SELECT `id`, `name`, `email`, `host`, `username`, `password`, `port`, `smtp_secure` 
                    FROM `config_emails` 
                    WHERE id = :id LIMIT 1";
    }
    
}