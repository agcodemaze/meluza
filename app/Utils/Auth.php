<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class Auth {

    // Conexão PDO para acessar o banco
    private static function getPDO(): PDO {
        $host   = $_ENV['ENV_BD_HOST'] ?? getenv('ENV_BD_HOST') ?? '';
        $dbname = $_ENV['ENV_BD_NAME'] ?? getenv('ENV_BD_NAME') ?? '';
        $user   = $_ENV['ENV_BD_USER'] ?? getenv('ENV_BD_USER') ?? '';
        $pass   = $_ENV['ENV_BD_PASS'] ?? getenv('ENV_BD_PASS') ?? '';
        $dsn  = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    

    public static function authCheck() {
        $secretKey = $_ENV['ENV_SECRET_KEY'] ?? getenv('ENV_SECRET_KEY') ?? '';
        $jwtLifetime = 60 * 60;          // 1 hora
        $refreshLifetime = 60 * 60 * 24 * 60; // 60 dias

        // 1️⃣ Verifica JWT
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            try {
                $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
                $userData = (array) $decoded->data;
                
                // Renova o JWT
                self::refreshJWT($userData, $secretKey, $jwtLifetime);
                self::defineUserConstants($userData);

                return; // ok, usuário autenticado
            } catch (\Exception $e) {
                // JWT expirou ou inválido, tentaremos usar o refresh token
            }
        }

        // 2️⃣ Se JWT não existe ou expirou, tenta o refresh token
        if (isset($_COOKIE['refresh_token'])) {
            $refreshToken = $_COOKIE['refresh_token'];
            $pdo = self::getPDO();
            $stmt = $pdo->prepare("SELECT * FROM RTK_REFRESH_TOKEN 
                                    WHERE RTK_STREVOKED = 0 
                                    AND RTK_DTEXPIRE_AT > NOW()");
            $stmt->execute();
            $tokens = $stmt->fetchAll();

            $userData = null;

            foreach ($tokens as $row) {
                // Como o token está hash, usamos password_verify
                if (password_verify($refreshToken, $row['RTK_DCTOKEN'])) {
                    $userStmt = $pdo->prepare("SELECT * FROM USU_USUARIO WHERE USU_IDUSUARIO = :id");
                    $userStmt->bindParam(':id', $row['USU_IDUSUARIO']);
                    $userStmt->execute();
                    $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
                    break;
                }
            }

            if ($userData) {
                // Renova JWT e refresh token
                self::refreshJWT($userData, $secretKey, $jwtLifetime);
                self::defineUserConstants($userData);
                return;
            }
        }

        // 3️⃣ Se nenhum token válido, força logout
        http_response_code(401);
        header("Location: /login");
        exit;
    }

    private static function refreshJWT($userData, $secretKey, $jwtLifetime) {
        $payload = [
            "iss" => "smilecopilot",
            "iat" => time(),
            "exp" => time() + $jwtLifetime,
            "data" => [
                "id" => $userData['USU_IDUSUARIO'] ?? $userData['id'],
                "email" => $userData['USU_DCEMAIL'] ?? $userData['email'],
                "perfil" => $userData['USU_ENPERFIL'] ?? $userData['perfil'],
                "nome" => $userData['USU_DCNOME'] ?? $userData['nome'],
                "tenancyid" => $userData['TENANCY_ID'] ?? $userData['tenancyid']
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        setcookie('token', $jwt, [
            'expires' => time() + $jwtLifetime,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    private static function defineUserConstants($userData) {
        define("USER_ID", $userData['USU_IDUSUARIO'] ?? $userData['id']);
        define("USER_NAME", $userData['USU_DCNOME'] ?? $userData['nome']);
        define("USER_EMAIL", $userData['USU_DCEMAIL'] ?? $userData['email']);
        define("PERFIL", $userData['USU_ENPERFIL'] ?? $userData['perfil']);
        define("TENANCY_ID", $userData['TENANCY_ID'] ?? $userData['tenancyid']);
    }
}
