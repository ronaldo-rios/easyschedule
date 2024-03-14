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
        'Nickname' => 'nickname',
        'Situação' => 'situation',
        'Criado em' => 'created_at',
        'Última atualização' => 'updated_at'
    ];

    foreach ($userDetails as $label => $key) {
        if (isset($this->data['viewUser'][$key])) {
            if ($key === 'image') {
                $imageSrc = $this->data['viewUser'][$key] ? $this->data['viewUser'][$key] : '';
                echo "<img src='". URL . "assets/images/users/" . $imageSrc . "' alt='{$this->data['viewUser']['name']}'><br>";
            } 
            elseif ($key === 'created_at' || $key === 'updated_at') {
            // Adjust the date format:
                $date = date_create_from_format('Y-m-d H:i:s', $this->data['viewUser'][$key]);
                echo "<span>{$label}: " . $date->format('d/m/Y') . "</span><br>";
            }
            else if ($key === 'situation') {
                // Define the color of the user's situation:
                echo "<span>{$label}: <span style='color: {$this->data['viewUser']['color']}'>
                    <strong>{$this->data['viewUser'][$key]}</strong>
                    </span></span><br>";
            }
            else {
                echo "<span>{$label}: {$this->data['viewUser'][$key]}</span><br>";
            }
        }
    }
}
