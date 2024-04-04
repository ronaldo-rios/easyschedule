<?php

isset($this->data['access_level']) && !empty($this->data['access_level'])
    ? $accessLevel = "<h2>Permissões do {$this->data['access_level']}</h2>"
    : $accessLevel = "<h2>Permissões</h2>";
    
echo $accessLevel; 

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['permissions'] as $permission) {
    if ($permission['permission'] === 'Liberado') {
        $statusPermission = 
        "<a href='" . URL . "edit-permission/index/{$permission['id']}&level={$permission['access_level_id']}&page={$this->data['page']}'>
            <span style='color: green;'>{$permission['permission']}
        </span></a>";
    }
    else {
        $statusPermission = 
        "<a href='" . URL . "edit-permission/index/{$permission['id']}&level={$permission['access_level_id']}&page={$this->data['page']}'>
            <span style='color: red;'>{$permission['permission']}
        </span></a>";
    }
    echo "<span>{$permission['name_page']} - {$permission['order_level_page']} - {$statusPermission} - {$permission['sidebar']}</span><br>";
    echo "<a href='". URL . "view-permission/index/{$permission['id']}'>Visualizar</a><br>";
    echo "<a href='". URL . "edit-permission/index/{$permission['id']}'>Editar</a><br><br>";
}

echo $this->data['pagination'];