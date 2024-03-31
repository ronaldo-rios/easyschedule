<?php

echo "<H2>Detalhes do usuário</H2>";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if(isset($this->data['viewPageGroup'])) {

    $details = [
        'Nome do grupo' => 'group_name',
        'Ordem do grupo' => 'order_page_group',
        'Criado em' => 'created_at',
        'Última atualização' => 'updated_at'
    ];

    foreach ($details as $label => $key) {
        $value = isset($this->data['viewPageGroup'][$key]) ? $this->data['viewPageGroup'][$key] : null;
        
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

    echo "<br><a href='". URL . "edit-page-group/index/{$this->data['viewPageGroup']['id']}'>Editar</a><br>";
}
