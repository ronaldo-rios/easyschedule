<?php 
ob_start(); // Inicia o buffer de saída para manipular a saída antes de enviá-la para o navegador
session_start(); 
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$home = new Core\ConfigController();
$home->loadPage();
