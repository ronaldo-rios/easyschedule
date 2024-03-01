<?php

namespace App\adms\Models\helpers;

use PDO;
use PDOException;

class Connection 
{
    private static string $host = HOST;
    private static string $user = USER;
    private static string $pass = PASSWORD;
    private static string $dbname = DB_NAME;
    private static string|int $port = PORT;
    private static ?PDO $connect = null;

    public static function connect(): PDO
    {
        if (self::$connect === null) {
            try {
                self::$connect = new PDO(
                    'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';port=' . self::$port,
                    self::$user,
                    self::$pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                        PDO::ATTR_EMULATE_PREPARES => false, 
                    ]
                );
            } 
            catch (PDOException $error) {
                die(
                    "Erro na conexão: {$error->getMessage()} - Código: {$error->getCode()}. <br>
                    Entre em contato com o administrador: " . ADM_EMAIL
                );
            }
        }
        
        return self::$connect;
    }
}