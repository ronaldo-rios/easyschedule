<?php

namespace Core;

abstract class Config
{
    protected function configAdm()
    {
        define('URL', 'http://localhost:5000/');
        // define('URL_ADM', 'http://localhost:5000/adm/');
        define('CONTROLLER', 'Login');
        define('METHOD', 'index');
        define('CONTROLLER_ERROR', 'Login');
        define('ADM_EMAIL', 'suporte@easyschedule.com');
        define('HOST', 'db');
        define('USER', 'root');
        define('PASSWORD', 'secret');
        define('DB_NAME', 'easy_schedule');
        define('PORT', 3306);
    }
}