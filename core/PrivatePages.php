<?php

namespace Core;

class PrivatePages
{
    public static function callPrivatePages(): array
    {
        return [
            "Dashboard", 
            "Users", 
            "ViewUser", 
            "EditUser", 
            "AddUser", 
            "DeleteUser",
            "ViewProfile",
            "EditProfile",
            "ViewEmailServers",
            "EditEmailServer",
            "AddEmailServer",
            "DeleteEmailServer",
            "AddAccessLevel",
            "EditAccessLevel",
            "AccessLevels",
            "DeleteAccessLevel",
            "PageGroups",
            "EditPageGroup",
            "AddPageGroup",
            "ViewPageGroup",
            "PageModules",
            "EditPageModule",
            "AddPageModule",
            "ViewPageModule",
            "Pages",
            "EditPage",
            "AddPage",
            "ViewPage",
            "Permissions",
            "SyncPageLevels",
        ];
    }
}