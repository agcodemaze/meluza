<?php
define('BASE_PATH', __DIR__ . '/');  // Define o caminho base do projeto
date_default_timezone_set('America/Sao_Paulo');

// Inclui as rotas definidas no arquivo 'routes.php'
$rotas = include 'routes.php';

// Pega a rota da URL amigável, caso contrário, usa a rota padrão 'login'
$route = $_GET['route'] ?? 'inicial';  

// Verifica se a rota existe no array de rotas e inclui a página correspondente
if (array_key_exists($route, $rotas)) {
    include $rotas[$route]; 
} else {
    include 'pages/errors/index.php';  // Caso a rota não exista, carrega uma página de erro
}
?>
