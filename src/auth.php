<?php
session_start(); 
if (!isset($_SESSION['jwt'])) {
    http_response_code(401);
    header("Location: /login"); 
    exit;
} 

require_once '/var/www/html/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$token = $_SESSION['jwt'];

try 
{
    $secretKey = getenv('ENV_SECRET_KEY');
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    $userData = (array) $decoded->data;

    define("USER_ID", $userData['id']);
    define("USER_NAME", $userData['nome']);
    define("USER_EMAIL", $userData['email']);    
    define("TELEFONE", $userData['telefone']);
    define("STPAGAMENTO", $userData['statusPagamento']);
    define("PLANOID", $userData['planoid']);
    define("DTCADASTRO", $userData['dtcadastro']);    

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Token inválido ou expirado!"]);
    header("Location: /login");
    exit;
}

