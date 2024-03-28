<?php

echo "<h2>Níveis de Acesso</h2>";

echo "<a href='". URL . "add-access-level/index'>Adicionar novo nível de acesso</a><br><br>";

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['accesslevels'] as $access) {
    echo "<span>{$access['access_level']}</span><br>";

    echo "<a href='". URL . "edit-access-level/index/{$access['id']}'>Editar</a><br>";
    echo "<a href='". URL . "delete-access-level/index/{$access['id']}' 
        onclick='return confirm(\"Tem certeza que deseja excluir este nível de acesso?\");'>Excluir</a><br><br>";

}