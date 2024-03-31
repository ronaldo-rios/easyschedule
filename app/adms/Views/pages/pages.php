<?php

echo "<h2>Páginas</h2>";

echo "<a href='". URL . "add-page/index'>Adicionar nova página</a><br><br>";

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['pages'] as $page) {
    echo "<span>{$page['name_page']} - {$page['module_type']} - {$page['module_name']}</span><br>";
    echo "<a href='". URL . "view-page/index/{$page['id']}'>Visualizar</a><br>";
    echo "<a href='". URL . "edit-page/index/{$page['id']}'>Editar</a><br><br>";
}

echo $this->data['pagination'];