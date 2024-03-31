<?php

echo "<h2>Módulos de Páginas</h2>";

echo "<a href='". URL . "add-page-module/index'>Adicionar novo módulo</a><br><br>";

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['modules'] as $module) {
    echo "<span>{$module['type']} - {$module['name']}</span><br>";
    echo "<a href='". URL . "view-page-module/index/{$module['id']}'>Visualizar</a><br>";
    echo "<a href='". URL . "edit-page-module/index/{$module['id']}'>Editar</a><br><br>";
}

echo $this->data['pagination'];