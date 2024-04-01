<?php

echo "<h2>Permissões</h2>";

echo "<a href='". URL . "add-user/index'>Adicionar nova permissão</a><br><br>";

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['permissions'] as $permission) {
    echo "<span>{$permission['name_page']} - {$permission['order_level_page']} - {$permission['permission']}</span><br>";
    echo "<a href='". URL . "view-permission/index/{$permission['id']}'>Visualizar</a><br>";
    echo "<a href='". URL . "edit-permission/index/{$permission['id']}'>Editar</a><br><br>";
}

echo $this->data['pagination'];