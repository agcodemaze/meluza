<?php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); //para ler o .env
$dotenv->load();

/**
 * nome do arquivo .php deve ser o mesmo nome da classe
 * por isso o \Home e na pasta do controoler existe um Home.php. Isso é padrão Composer
 */

use \App\Http\Router;
use \App\Http\Response;
use \App\Controller\Pages\Home; 
use \App\Controller\Pages\CadPaciente; 

define('URL','https://cliente.meluza.com.br');
define('ASSETS_PATH', '/public/assets/');
define('UXCOMPONENTS_PATH', __DIR__ . '/UX_Components/');

$obRouter = new Router(URL);

//ROTA HOME
$obRouter->get('/',[
    function(){
        return new Response(200,Home::getHome());
    }
]);

//ROTA CAD PACIENTES
$obRouter->get('/cadastropaciente',[
    function(){
        return new Response(200,CadPaciente::getCadPaciente());
    }
]);


//IMPRIME RESPONSE NA PÁGINA
$obRouter->run()
            ->sendResponse();




