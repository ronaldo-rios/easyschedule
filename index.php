<?php 
ob_start(); // Inicia o buffer de saída para manipular a saída antes de enviá-la para o navegador
session_start(); 
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Easy Schedule</title>
    </head>
    <body>

        <?php 
            require_once __DIR__ . '/vendor/autoload.php';

            $home = new Core\ConfigController();
            $home->loadPage();
        ?>

    </body>
</html>