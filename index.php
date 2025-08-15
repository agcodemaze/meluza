<?php

require __DIR__.'/vendor/autoload.php';

/**
 * nome do arquivo .php deve ser o mesmo nome da classe
 * por isso o \Home e na pasta do controoler existe um Home.php. Isso é padrão Composer
 */

use \App\Http\Router;
use \App\Http\Response;
use \App\Controller\Pages\Home; 

define('URL','https://cliente.meluza.com.br');

$obRouter = new Router(URL);

//RORA HOME
$obRouter->get('/',[
    function(){
        return new Response(200,Home::getHome());
    }
])




