

<?php 
if (isset($this->data['sidebar_menu']) && is_array($this->data['sidebar_menu'])) {
    
    $permittedControllers = array_column(
        $this->data['sidebar_menu'], 'controller_in_the_main'
    );

    $controllersInTheMain = [
        'dashboard' => 'Dashboard',
        'users' => 'Usuários',
        'view-profile' => 'Perfil',
        'email-servers' => 'Servidores de Email',
        'access-levels' => 'Níveis de Acesso',
        'page-groups' => 'Grupos de Páginas',
        'page-modules' => 'Módulos',
        'pages' => 'Páginas',
        'logout' => 'Sair'
    ];

    foreach ($controllersInTheMain as $controller => $label) {
        if (in_array($controller, $permittedControllers)) {
            echo '<a href="' . URL . $controller . '/index">' . $label . '</a><br>';
        }
    }
}