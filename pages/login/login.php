<?php
session_start();
require_once '/var/www/html/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include_once "../../objects/objects.php";

class LoginSystem extends SITE_ADMIN
{
    public function validateUser($email, $password)
    {
        try {
            if (!$this->pdo) {
                $this->conexao(); 
            }

            $response = $this->loginSistema($email, $password);
            $data = json_decode($response, true);            

            if ($data['success'] === true) 
            {               
                session_regenerate_id(true);
                //$this->insertLogInfo("auth", "SISTEMA", "-", "-","A tentativa de login com o usuário $email teve êxito. Aguardando a geração do JWT.");
                echo $response;
            } 
            else 
                {
                    // ATRASO para dificultar brute force
                    sleep(2); // 2 segundos de atraso
                    //$this->insertLogInfo("auth", "SISTEMA", "-", "-","A tentativa de login com o $email falhou. Não foi possível gerar o JWT.");
                    echo $response;
                }
        } catch (PDOException $e) {   
            $erro = $e->getMessage();
            //$this->insertLogInfo("Error", "SISTEMA", "-", "-","Houve um erro sistêmico durante o processo de login com o e-mail: $email: $erro");
            echo json_encode(["success" => false, "message" => "Erro no servidor. Tente novamente mais tarde."]);
        }
    }
}

// Processa a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? null;
    $password = $data['senha'] ?? null;

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Todos os campos são obrigatórios."]);
        exit;
    }

    $loginSystem = new LoginSystem();
    $loginSystem->validateUser($email, $password);
}

?> 

