<?php
namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

/**
 * A classe Auth gerencia a validação de tokens de autenticação
 * e o fluxo de login para a aplicação.
 */
class Auth {

    /**
     * Cria e retorna uma conexão PDO com o banco de dados.
     *
     * Este método estático encapsula a lógica de conexão com o banco de dados,
     * garantindo que os atributos de erro e de busca padrão estejam definidos.
     *
     * @return PDO Retorna um objeto PDO para interagir com o banco de dados.
     */
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

    /**
     * Verifica a autenticação do usuário com base nos cookies JWT e Refresh Token.
     *
     * Este método tenta validar o JWT presente no cookie. Se o JWT for inválido
     * ou estiver expirado, ele tenta usar o refresh token. Se ambos falharem,
     * o usuário é redirecionado para a página de login.
     *
     * @return void Esta função não retorna nada em caso de sucesso. Em caso de falha,
     * ela força o encerramento do script e redireciona o usuário.
     */
    public static function authCheck() {
        $secretKey = $_ENV['ENV_SECRET_KEY'] ?? getenv('ENV_SECRET_KEY') ?? '';
        $jwtLifetime = 60 * 60;          // 1 hora
        $refreshLifetime = 60 * 60 * 24 * 60; // 60 dias

        // Verifica JWT
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

        // Se JWT não existe ou expirou, tenta o refresh token
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

        // Se nenhum token válido, força logout
        http_response_code(401);
        header("Location: /login");
        exit;
    }

    /**
     * Gera e define um novo token JWT no cookie.
     *
     * Este método é responsável por renovar o JWT, redefinindo o tempo de expiração
     * e o conjunto de dados do usuário. O novo token é armazenado no cookie 'token'.
     *
     * @param array $userData As informações do usuário para o payload do JWT.
     * @param string $secretKey A chave secreta usada para codificar o token.
     * @param int $jwtLifetime O tempo de vida do token em segundos.
     * @return void
     */
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

    /**
     * Define constantes PHP globais com base nos dados do usuário.
     *
     * Este método cria um conjunto de constantes de fácil acesso
     * com as informações do usuário logado.
     *
     * @param array $userData Um array com os dados do usuário.
     * @return void
     */
    private static function defineUserConstants($userData) {
        define("USER_ID", $userData['USU_IDUSUARIO'] ?? $userData['id']);
        define("USER_NAME", $userData['USU_DCNOME'] ?? $userData['nome']);
        define("USER_EMAIL", $userData['USU_DCEMAIL'] ?? $userData['email']);
        define("PERFIL", $userData['USU_ENPERFIL'] ?? $userData['perfil']);
        define("TENANCY_ID", $userData['TENANCY_ID'] ?? $userData['tenancyid']);
    }
}
