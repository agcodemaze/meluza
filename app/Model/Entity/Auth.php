<?php 

namespace App\Model\Entity;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use \App\Model\Entity\Conn;
use PDO;
use PDOException;

/**
 * A classe Auth herda de Conn para gerenciar a autenticação de usuários
 * e a geração de tokens JWT.
 */
class Auth extends Conn { 

    /**
     * Autentica um usuário verificando email, senha e tenancy ID.
     * @param string $USU_DCEMAIL O email do usuário.
     * @param string $USU_DCSENHA A senha do usuário.
     * @param string $TENANCY_ID O ID da tenancy.
     * @return string Retorna um JSON com o resultado da autenticação.
     */
    public function autenticar($USU_DCEMAIL, $USU_DCSENHA, $TENANCY_ID) {

        $sql = "SELECT * FROM USU_USUARIO WHERE USU_DCEMAIL = :USU_DCEMAIL AND TENANCY_ID = :TENANCY_ID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":USU_DCEMAIL", $USU_DCEMAIL);
        $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
        $stmt->execute();
        $userinfo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userinfo && password_verify($USU_DCSENHA, $userinfo['USU_DCSENHA'])) 
        {
            $this->GenJWT($userinfo);
            return json_encode(["success" => true, "message" => "Credenciais válidas!"]);
        }
        else
            {                
                return json_encode(["success" => false, "message" => "Credenciais inválidas!"]);
            }
    }


    /**
     * Gera um token JWT e um refresh token, e os armazena em cookies HTTP-only.
     *
     * Esta função cria um JWT com as informações do usuário, define cookies HTTP-only
     * para o JWT e um refresh token, e armazena o refresh token no banco de dados
     * para posterior revogação ou atualização.
     *
     * @param array $userinfo As informações do usuário autenticado (ID, email, perfil, nome, tenancy ID).
     * @return void Esta função não retorna um valor, apenas executa a lógica de autenticação.
     */
    function GenJWT($userinfo) {
        $secretKey   = $_ENV['ENV_SECRET_KEY'] ?? getenv('ENV_SECRET_KEY') ?? '';

        // Tempo de vida do JWT e do refresh token
        $jwtLifetime = 60 * 60;          // 1 hora
        $refreshLifetime = 60 * 60 * 24 * 60; // 60 dias

        $payload = [
            "iss" => "smilecopilot",
            "iat" => time(),
            "exp" => time() + $jwtLifetime,
            "data" => [
                "id" => $userinfo['USU_IDUSUARIO'],
                "email" => $userinfo['USU_DCEMAIL'],
                "perfil" => $userinfo['USU_ENPERFIL'],
                "nome" => $userinfo['USU_DCNOME'],
                "tenancyid" => $userinfo['TENANCY_ID']
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        // Cookie HTTP-only para JWT
        setcookie('token', $jwt, [
            'expires' => time() + $jwtLifetime,
            'path' => '/',
            //'domain' => 'smilecopilot.com',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        // Gerar refresh token aleatório
        $refreshToken = bin2hex(random_bytes(64));

        // Armazenar refresh token no banco
        $this->putRefreshToken($userinfo['USU_IDUSUARIO'], $refreshToken, $refreshLifetime);

        // Cookie HTTP-only para refresh token
        setcookie('refresh_token', $refreshToken, [
            'expires' => time() + $refreshLifetime,
            'path' => '/',
            //'domain' => 'smilecopilot.com',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    /**
     * Armazena o refresh token no banco de dados.
     *
     * Esta função insere um novo refresh token para um usuário no banco de dados,
     * juntamente com seu tempo de expiração e status de revogação.
     * O token é armazenado com hash para maior segurança.
     *
     * @param int $USU_IDUSUARIO O ID do usuário associado ao token.
     * @param string $RTK_DCTOKEN O token de atualização gerado.
     * @param int $lifetime O tempo de vida do token em segundos.
     * @return void
     */
    private function putRefreshToken($USU_IDUSUARIO, $RTK_DCTOKEN, $lifetime) {
        // Opcional: armazenar hash para mais segurança
        $hashedToken = password_hash($RTK_DCTOKEN, PASSWORD_DEFAULT);

        $sql = "INSERT INTO RTK_REFRESH_TOKEN (USU_IDUSUARIO, RTK_DCTOKEN, RTK_DTEXPIRE_AT, RTK_DTCREATE_AT, RTK_STREVOKED)
                VALUES (:USU_IDUSUARIO, :RTK_DCTOKEN, :RTK_DTEXPIRE_AT, NOW(), 0)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO);
        $stmt->bindParam(':RTK_DCTOKEN', $hashedToken);
        $RTK_DTEXPIRE_AT = date('Y-m-d H:i:s', time() + $lifetime);
        $stmt->bindParam(':RTK_DTEXPIRE_AT', $RTK_DTEXPIRE_AT);
        $stmt->execute();
    }

    /**
     * Realiza o logoff do usuário, limpando sessões, cookies e revogando o refresh token.
     *
     * Esta função destrói a sessão, remove os cookies (de sessão, JWT e refresh token),
     * e marca o refresh token no banco de dados como revogado para invalidá-lo.
     * Após a limpeza, redireciona o usuário para a página de login.
     *
     * @return void
     */
    function logoff() {
    
        // Remover cookie de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => 'Lax'
            ]);
        }
    
        // Remover JWT
        setcookie('token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            //'domain' => 'smilecopilot.com.br',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    
        // Revogar refresh token no banco
        if (isset($_COOKIE['refresh_token'])) {
            $refreshToken = $_COOKIE['refresh_token'];
        
            // Marca como revogado
            $stmt = $this->pdo->prepare("UPDATE RTK_REFRESH_TOKEN SET RTK_STREVOKED = 1 WHERE RTK_STREVOKED = 0");
            $stmt->execute();
        }
    
        // Remover cookie de refresh token
        setcookie('refresh_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            //'domain' => 'smilecopilot.com.br',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    
        // Limpar localStorage e redirecionar
        echo "<script>
                localStorage.removeItem('jwt');
                window.location.href = '/login';
                </script>";
        exit;
    }
}