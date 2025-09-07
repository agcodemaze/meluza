<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Auth;

class Login extends PageLogin{

    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getLogin() {

        $objOrganization = new Organization();
        
        //VIEW DA HOME
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'keywords' => $objOrganization->keywords
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_login', $content);
    }

    public function validateUser($email, $password, $codigo)
    {
        try {

            $loginVerificar = new Auth();

            $response = $loginVerificar->autenticar($email, $password, $codigo);
            $data = json_decode($response, true);            

            if ($data['success'] === true) 
            {               
                //session_regenerate_id(true);                
                echo $response;
            } 
            else 
                {
                    // ATRASO para dificultar brute force
                    sleep(2); // 2 segundos de atraso                    
                    echo $response;
                }
        } catch (PDOException $e) {   
            $erro = $e->getMessage();           
            echo json_encode(["success" => false, "message" => "Erro no servidor. Tente novamente mais tarde."]);
        }
    }    

    public static function logoffUser()
    {
        $logoff = new Auth();
        $response = $logoff->logoff();            
    }  
}