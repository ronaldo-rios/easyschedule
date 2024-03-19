<?php

echo "<h2>Usuários</h2>";

echo "<a href='". URL . "add-user/index'>Adicionar novo usuário</a><br><br>";

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['users'] as $user) {
    echo "<span>{$user['name']} - {$user['email']} - {$user['user']}</span><br>";
    echo "<a href='". URL . "view-user/index/{$user['id']}'>Visualizar</a><br>";
    echo "<a href='". URL . "edit-user/index/{$user['id']}'>Editar</a><br>";
    echo "<a href='". URL . "delete-user/index/{$user['id']}'>Excluir</a><br><br>";
}