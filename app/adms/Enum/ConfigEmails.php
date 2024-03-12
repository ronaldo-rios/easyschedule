<?php

namespace App\adms\Enum;

enum ConfigEmails: int
{
    case REGISTER_CONFIRMATION = 1;
    case RECOVER_PASSWORD = 2;
    case CONTACT = 3;
    case NEWSLETTER = 4;
}