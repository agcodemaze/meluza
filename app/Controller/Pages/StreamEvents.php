<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Eventos;

class StreamEvents {
    
    public function streamConsultaEventos()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        set_time_limit(0); // script infinito permitido
        ob_implicit_flush(true);

        $ultimoId = 0;
        $eventosStream = new Eventos();
        $inicio = time();

        while (true) {

            if (connection_aborted()) break; // sai se o cliente fechou

            if (time() - $inicio > 300) { // encerra após 5 minutos
                echo "event: close\n";
                echo "data: Conexão encerrada\n\n";
                flush();
                break;
            }

            $eventos = $eventosStream->getEventsStream($ultimoId);

            if (!empty($eventos)) {
                foreach ($eventos as $evento) {
                    echo "event: statusUpdate\n";
                    echo "data: " . json_encode($evento) . "\n\n";
                    flush();
                    $ultimoId = $evento['EVE_IDEVENTOS'];
                }
            }

            sleep(2);
        }
    }


}

