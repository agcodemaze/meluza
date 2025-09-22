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
}