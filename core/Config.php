<?php

namespace Core;

abstract class Config
{
    protected function configAdm()
    {
        define('URL', $_ENV['URL_BASE']);
        define('CONTROLLER', 'Login');
        define('METHOD', 'index');
        define('CONTROLLER_ERROR', 'Login');
        define('ADM_EMAIL', $_ENV['ADM_EMAIL']);
        define('HOST', $_ENV['MYSQL_HOST']);
        define('USER', $_ENV['MYSQL_USER']);
        define('PASSWORD', $_ENV['MYSQL_PASSWORD']);
        define('DB_NAME', $_ENV['MYSQL_DATABASE']);
        define('PORT', $_ENV['MYSQL_PORT']);
        define('PATH_USER_IMAGE', 'app/adms/assets/image/users/');
    }
}