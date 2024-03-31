<?php

echo "<H2>Detalhes do usuário</H2>";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if(isset($this->data['viewPageModule'])) {

    $details = [
        'Tipo de módulo' => 'type', 
        'Nome do módulo' => 'name',
        'Ordem do módulo' => 'order_module',
        'Observações' => 'obs',
        'Criado em' => 'created_at',
        'Última atualização' => 'updated_at'
    ];

    foreach ($details as $label => $key) {
        $value = isset($this->data['viewPageModule'][$key]) ? $this->data['viewPageModule'][$key] : null;
        
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

    echo "<br><a href='". URL . "edit-page-module/index/{$this->data['viewPageModule']['id']}'>Editar</a><br>";
}
