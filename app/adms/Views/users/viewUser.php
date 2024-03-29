<?php

echo "<H2>Detalhes do usuário</H2>";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if(isset($this->data['viewUser'])) {

    $userDetails = [
        'Imagem' => 'image',
        'Nome' => 'name',
        'Email' => 'email',
        'Usuário' => 'user',
        'Apelido' => 'nickname',
        'Situação' => 'situation',
        'Nível de Permissão' => 'access_level',
        'Criado em' => 'created_at',
        'Última atualização' => 'updated_at'
    ];

    foreach ($userDetails as $label => $key) {
        $value = isset($this->data['viewUser'][$key]) ? $this->data['viewUser'][$key] : null;
        
        if ($key === 'image') {

            $userIdAndImage = $this->data['viewUser']['id'] . "/". $value;
            $imageSrc = $value !== null 
                ? URL . PATH_USER_IMAGE . $userIdAndImage
                : URL . PATH_USER_IMAGE . 'default.png';

            echo "<img style='width:10em; height:9em;' src='". $imageSrc . "' alt='{$this->data['viewUser']['name']}'><br>";
        } 
        elseif ($key === 'created_at' || $key === 'updated_at') {
        // Adjust the date format and verify if the date is not null:
            if ($value !== null) {
                $date = date_create_from_format('Y-m-d H:i:s', $value);
                if ($date !== false) {
                    echo "<span>{$label}: " . $date->format('d/m/Y') . "</span><br>";
                }
            }
        }
        else if ($key === 'situation') {
            // Define the color of the user's situation:
            echo "<span>{$label}: <span style='color: {$this->data['viewUser']['color']}'>
            <strong>{$value}</strong>
            </span></span><br>";
        }
        else {
            if($value !== null) {
                echo "<span>{$label}: {$value}</span><br>";
            }
        }
        
    }

    echo "<br><a href='". URL . "edit-user/index/{$this->data['viewUser']['id']}'>Editar</a><br>";
    echo "<a href='". URL . "delete-user/index/{$this->data['viewUser']['id']}' 
        onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\");'>Excluir</a><br><br>";
}
