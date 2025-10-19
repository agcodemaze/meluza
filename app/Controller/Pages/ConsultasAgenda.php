<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Consultas;
use \App\Utils\Auth;

/**
 * A classe Login é responsável por controlar a página de login
 * e as ações de autenticação do usuário.
 */
class ConsultasAgenda{

    public function getHorariosDisp($data, $duracao) {
        Auth::authCheck(); //verifica se já tem login válido (jwt)
        try {
                $objConsultas = new Consultas();
                $response = $objConsultas->getHorariosDisponiveis($data, "30", TENANCY_ID);
                //$data = json_decode($response, true);            
                echo json_encode($response);               
                return json_encode($response);             


        } catch (PDOException $e) {   
            $erro = $e->getMessage();           
            return json_encode(["success" => false, "message" => "Erro no servidor. Tente novamente mais tarde."]);
        }
    }  
    
    public function updateConsulta($id, $start, $end) {
        Auth::authCheck();

        try {
                $objConsultas = new Consultas();

                $startDate = new \DateTime($start);
                $endDate   = $end ? new \DateTime($end) : null;

                $dataConsulta = $startDate->format('Y-m-d');

                $horaInicio = $startDate->format('H:i:s');

                $duracao = 0;
                if ($endDate) {
                    $intervalo = $startDate->diff($endDate);
                    $duracao = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;
                }

                if($duracao > 60)
                {
                    return json_encode(["success" => false, "message" => "Consulta não pode ter duração maior que 1 hora."]);
                }

                $response = $objConsultas->updateConsultaAgenda($id, $dataConsulta, $horaInicio, $duracao, TENANCY_ID);

            return json_encode(["success" => true, "message" => "Consulta atualizada com sucesso."]);
        
        } catch (PDOException $e) {   
            $erro = $e->getMessage();           
            return json_encode(["success" => false, "message" => "Erro no servidor. Tente novamente mais tarde."]);
        }
    } 
}