<?php

echo "<h2>Grupos de Páginas</h2>";

echo "<a href='". URL . "add-page-group/index'>Adicionar novo grupo</a><br><br>";

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['page_groups'] as $group) {
    echo "<span>{$group['group_name']}</span><br>";
    echo "<a href='". URL . "view-page-group/index/{$group['id']}'>Visualizar</a><br>";
    echo "<a href='". URL . "edit-page-group/index/{$group['id']}'>Editar</a><br><br>";
}

echo $this->data['pagination'];