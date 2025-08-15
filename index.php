<?php

require __DIR__.'/vendor/autoload.php';

/**
 * nome do arquivo .php deve ser o mesmo nome da classe
 * por isso o \Home e na pasta do controoler existe um Home.php. Isso é padrão Composer
 */

use \App\Http\Router;
use \App\Controller\Pages\Home; 

define('URL','https://cliente.meluza.com.br');

$obRouter = new Router(URL);

        echo "<pre>";   
        print_r($obRouter);
        echo "<pre>"; 
        exit;

exit;
echo Home::getHome();


