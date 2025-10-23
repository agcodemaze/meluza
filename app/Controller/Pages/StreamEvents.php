<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Eventos;

class StreamEvents {
    
    public function getNovosEventos($ultimoId = 0)
    {
        $eventos = (new \App\Model\Entity\Eventos())->getEventsStream($ultimoId);
        return $eventos;
    }

}

