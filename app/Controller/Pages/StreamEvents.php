<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Eventos;

class StreamEvents {
    
    public function streamConsultaEventos()
    {
        header('Content-Type: text/event-stream; charset=utf-8');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        set_time_limit(0); // permite execução longa

        while (ob_get_level() > 0) ob_end_flush(); // limpa buffers abertos

        $ultimoId = 0;
        $eventosStream = new Eventos();
        $inicio = time();

        while (true) {

            if (connection_aborted()) break; // sai se cliente fechou

            if (time() - $inicio > 300) { // encerra após 5 min
                echo "event: close\n";
                echo "data: Conexão encerrada\n\n";
                ob_flush();
                flush();
                break;
            }

            $eventos = $eventosStream->getEventsStream($ultimoId);

            if (!empty($eventos)) {
                foreach ($eventos as $evento) {
                    echo "event: statusUpdate\n";
                    echo "data: " . json_encode($evento) . "\n\n";
                    ob_flush();
                    flush();
                    $ultimoId = $evento['EVE_IDEVENTOS'];
                }
            }

            sleep(2); // evita loop muito rápido
        }
    }

}

