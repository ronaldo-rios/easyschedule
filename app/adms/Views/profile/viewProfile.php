<?php

echo "<H2>Meu Perfil</H2>";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if(isset($this->data['profile'])) {

    $infoProfile = [
        'Imagem' => 'image',
        'Nome' => 'name',
        'Email' => 'email',
        'Usuário' => 'user',
        'Apelido' => 'nickname'
    ];

    foreach ($infoProfile as $label => $key) {
        $value = isset($this->data['profile'][$key]) ? $this->data['profile'][$key] : null;
        
        if ($key === 'image') {
            
            $userIdAndImage = $_SESSION['user_id'] . "/". $value;
            $imageSrc = $value !== null 
                ? URL . PATH_USER_IMAGE . $userIdAndImage
                : URL . PATH_USER_IMAGE . 'default.png';

            echo "<img style='width:10em; height:9em;' src='". $imageSrc . "' alt='{$this->data['profile']['name']}'><br>";
        } 
        else {
            echo "<span>{$label}: {$value}</span><br>";
        }
        
    }

}