<?php
require_once '/var/www/html/vendor/autoload.php';  
include_once "../../objects/objects.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start();

header("Content-Type: application/json");

$siteAdmin = new SITE_ADMIN();  

$secretKey = getenv('ENV_SECRET_KEY');

if (empty($secretKey)) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erro de configuração do servidor. Verifique as váriaveis de ambiente."]);
    exit;
}

$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

$userinfo = $data['userinfo'] ?? null;

if (!$userinfo) {   
    //$siteAdmin->insertLogInfo("error", "SISTEMA", "-", "-","Houve um erro sistêmico durante a geração do JWT (dados incompletos).");
    echo json_encode(["success" => false, "message" => "Dados incompletos"]);
    exit;
}

$payload = [
    "iss" => "meluza",
    "iat" => time(),
    "exp" => time() + 43200,
    "data" => [
        "id" => $userinfo['USU_IDUSUARIO'],
        "email" => $userinfo['USU_DCEMAIL'],
        "telefone" => $userinfo['USU_DCTELEFONE'],
        "nome" => $userinfo['USU_DCNOME'],
        "statusPagamento" => $userinfo['USU_STPAGAMENTO'],
        "planoid" => $userinfo['PLA_IDPLANO'], 
        "dtcadastro" => $userinfo['USU_DTCADASTRO']
    ]
];

$jwt = JWT::encode($payload, $secretKey, 'HS256');
$_SESSION['jwt'] = $jwt;

//$siteAdmin->insertLogInfo("auth", "SISTEMA", "-", "-","O Token foi gerado com sucesso para o usuário ".$userinfo['USU_DCEMAIL']);

echo json_encode(["success" => true]);
