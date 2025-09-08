<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Auth;

/**
 * A classe Login é responsável por controlar a página de login
 * e as ações de autenticação do usuário.
 */
class Login extends PageLogin{

    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */
    public static function getLogin() {

        $objOrganization = new Organization();
        
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'keywords' => $objOrganization->keywords
        ]); 

        return self::getPage('pages/vw_login', $content);
    }

    /**
     * Valida as credenciais do usuário.
     *
     * Este método autentica o usuário com base no email, senha e código de
     * tenancy, retornando um JSON com o resultado da validação. Em caso de
     * falha, introduz um atraso para mitigar ataques de força bruta.
     *
     * @param string $email O email do usuário.
     * @param string $password A senha do usuário.
     * @param string $codigo O código de tenancy (ID).
     * @return string Retorna uma string JSON contendo o status de sucesso e uma mensagem.
     */
    public function validateUser($email, $password, $codigo) {
        try {
                $loginVerificar = new Auth();
                $response = $loginVerificar->autenticar($email, $password, $codigo);
                $data = json_decode($response, true);            

                if ($data['success'] === true) 
                {                        
                    return $response;                
                } 
                else 
                    {
                        sleep(2); // 2 segundos de atraso dificultar bruteforce                   
                        return $response;
                    }
        } catch (PDOException $e) {   
            $erro = $e->getMessage();           
            return json_encode(["success" => false, "message" => "Erro no servidor. Tente novamente mais tarde."]);
        }
    }    

    /**
     * Redireciona para o método de logoff do usuário.
     *
     * Este método atua como uma ponte para a função de logoff na classe Auth,
     * garantindo que a lógica de desautenticação seja centralizada.
     *
     * @return void
     */
    public static function logoffUser() {
        $logoff = new Auth();
        $response = $logoff->logoff();            
    }  
}