<?php

echo "<h2>Usuários</h2>";

if ($this->data['button_permissions']['add_user']) {
    echo "<a href='". URL . "add-user/index'>Adicionar novo usuário</a><br><br>";
}

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

foreach($this->data['users'] as $user) {
    echo "<span>{$user['name']} - {$user['email']} - {$user['user']}</span><br>";

    echo $this->data['button_permissions']['view_user'] 
        ? "<a href='". URL . "view-user/index/{$user['id']}'>Visualizar</a><br>" : '';
    echo $this->data['button_permissions']['edit_user'] 
        ? "<a href='". URL . "edit-user/index/{$user['id']}'>Editar</a><br>" : '';
    echo $this->data['button_permissions']['delete_user'] 
        ? "<a href='". URL . "delete-user/index/{$user['id']}' 
            onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\");'>Excluir</a><br><br>"
        : '';

}

echo $this->data['pagination'];