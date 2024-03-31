<?php

echo "<H2>Detalhes da página</H2>";

if(isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if(isset($this->data['viewPage'])) {
    
    $details = [
        'Nome da página' => 'name_page',
        'Tipo de módulo' => 'type', 
        'Nome do módulo' => 'name',
        'Grupo pertencente' => 'group_name',
        'Status da página' => 'status',
        'Ordem do módulo' => 'order_module',
        'Observações' => 'obs',
        'Pública?' => 'public',
        'Criado em' => 'created_at',
        'Última atualização' => 'updated_at'
    ];

    foreach ($details as $label => $key) {
        $value = isset($this->data['viewPage'][$key]) ? $this->data['viewPage'][$key] : null;
        
        if ($key === 'created_at' || $key === 'updated_at') {
        // Adjust the date format and verify if the date is not null:
            if ($value !== null) {
                $date = date_create_from_format('Y-m-d H:i:s', $value);
                if ($date !== false) {
                    echo "<span>{$label}: " . $date->format('d/m/Y') . "</span><br>";
                }
            }
        }
        else if ($key === 'public') {
            if ($value === '1') {
                echo "<span>{$label} Sim</span><br>";
            }
            else {
                echo "<span>{$label} Não</span><br>";
            }
        }
        else if ($key === 'obs'){
            if($value !== null) {
                echo "<span>{$label}: {$value}</span><br>";
            }
        }
        else if ($key === 'status') {
            // Define the color of the user's situation:
            echo "<span>{$label}: <span style='color: {$this->data['viewPage']['color']}'>
            <strong>{$value}</strong>
            </span></span><br>";
        }
        else {
            if($value !== null) {
                echo "<span>{$label}: {$value}</span><br>";
            }
        }
        
    }

    echo "<br><a href='". URL . "edit-page/index/{$this->data['viewPage']['id']}'>Editar</a><br>";
}
