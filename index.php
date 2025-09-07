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
use \App\Controller\Pages\CadAnamnese; 
use \App\Controller\Pages\Agenda; 
use \App\Controller\Pages\ListPaciente; 
use \App\Controller\Pages\Login; 
use App\Core\Language;

// Inicia sistema de idiomas
Language::init();

define('URL','https://cliente.meluza.com.br');
define('ASSETS_PATH', '/public/assets/');
define('UXCOMPONENTS_PATH', __DIR__ . '/UX_Components/');
define('TENANCY_ID','1');

$obRouter = new Router(URL);

//ROTA HOME
$obRouter->get('/inicial',[
    function(){
        return new Response(200,Home::getHome());
    }
]);

//ROTA CAD PACIENTES
$obRouter->get('/cadastropaciente',[
    function(){
        return new Response(200,CadPaciente::putCadPaciente());
    }
]);

//ROTA LIST PACIENTES
$obRouter->get('/listapaciente',[
    function(){
        return new Response(200,ListPaciente::getPaciente());
    }
]);

//ROTA CAD ANAMNESE
$obRouter->get('/anamnese',[
    function(){
        return new Response(200,CadAnamnese::getAnamnese());
    }
]);

//ROTA AGENDA
$obRouter->get('/agenda',[
    function(){
        return new Response(200,Agenda::getAgenda());
    }
]);

//ROTA LOGIN
$obRouter->get('/login',[
    function(){
        return new Response(200,Login::getLogin());
    }
]);


//IMPRIME RESPONSE NA PÁGINA
$obRouter->run()
            ->sendResponse();




