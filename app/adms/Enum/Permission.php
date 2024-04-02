<?php

namespace App\adms\Enum;

enum Permission: int
{
    case HAVE_PERMISSION = 1;
    case NO_PERMISSION = 0;
}