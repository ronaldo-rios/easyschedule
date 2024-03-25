<?php

echo "<H2>Servidores de Email</H2>";

echo "<a href='". URL . "add-email-server/index'>Adicionar novo servidor de e-mail</a><br>";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if(isset($this->data['emailServers'])) {

    $infoProfile = [
        'Nome' => 'name',
        'Host' => 'host',
        'Porta' => 'port',
        'Usuário' => 'username',
        'Email' => 'email',
        'Smtp' => 'smtp_secure',
        'Criado em' => 'created_at',
        'Atualizado em' => 'updated_at'
    ];

    foreach($this->data['emailServers'] as $emailServer) {
        echo "<br>";
        foreach ($infoProfile as $label => $key) {
            $value = isset($emailServer[$key]) ? $emailServer[$key] : null;

            if ($key === 'created_at' || $key === 'updated_at') {
                // Adjust the date format and verify if the date is not null:
                if ($value !== null) {
                    $date = date_create_from_format('Y-m-d H:i:s', $value);
                    if ($date !== false) {
                        echo "<span>{$label}: " . $date->format('d/m/Y') . "</span><br>";
                    }
                }
            }
            else {
                if($value !== null) {
                    echo "<span>{$label}: {$value}</span><br>";
                }
            
            }
        }
        echo "<a href='". URL . "edit-email-server/index/{$emailServer['id']}'>Editar</a><br>";
        echo "<a href='". URL . "delete-email-server/index/{$emailServer['id']}' 
            onclick='return confirm(\"Tem certeza que deseja excluir este registro?\");'>Excluir</a><br><br>";
    }

}