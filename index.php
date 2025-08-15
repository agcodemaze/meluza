<?php

require __DIR__.'/vendor/autoload.php';

/**
 * nome do arquivo .php deve ser o mesmo nome da classe
 * por isso o \Home e na pasta do controoler existe um Home.php. Isso é padrão Composer
 */

use \App\Controller\Pages\Home; 

$responde = new \App\Http\Response(200,'Olá Mundo');

$responde->sendResponse();

exit;
echo Home::getHome();


