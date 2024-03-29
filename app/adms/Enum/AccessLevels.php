<?php

namespace App\adms\Enum;

enum AccessLevels: int
{
    case MASTER = 1;
    case ADMIN = 2; 
    case USER_DEFAULT = 3;
}