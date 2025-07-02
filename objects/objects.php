<?php
require_once '/var/www/html/vendor/autoload.php';  
use Firebase\JWT\JWT;

include realpath(__DIR__ . '/../phpMailer/src/PHPMailer.php');
include realpath(__DIR__ . '/../phpMailer/src/SMTP.php');
include realpath(__DIR__ . '/../phpMailer/src/Exception.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

	class SITE_ADMIN 
	{
        public $pdo; 

        function conexao()
        {            
            $host = $_ENV['ENV_BD_HOST'];
            $dbname = $_ENV['ENV_BD_NAME'];
            $user = $_ENV['ENV_BD_USER'];
            $pass = $_ENV['ENV_BD_PASS'];          

            try {
                $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            } 
        }

        function loginSistema($USU_DCEMAIL, $USU_DCSENHA) 
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            // Prepara a consulta SQL para verificar o usuário
            $sql = "SELECT * FROM USU_USUARIO WHERE USU_DCEMAIL = :USU_DCEMAIL";
            $stmt = $this->pdoSistema->prepare($sql);
            $stmt->bindParam(':USU_DCEMAIL', $USU_DCEMAIL, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($USU_DCSENHA, $user['USU_DCSENHA'])) 
            {
                $arrayCondominios = $this->getCondominiosByUserId($user['USU_IDUSUARIO']); //busca lista de condo  

                if(count($arrayCondominios) == 0)
                {
                    return json_encode(["success" => false, "message" => "Usuário não está cadastrado em nenhum condomínio."]);
                }

                return json_encode(["success" => true, "message" => "Credenciais válidas!", "userinfo" => $user, "condominios" => $arrayCondominios]);
            }
            else
                {                
                    return json_encode(["success" => false, "message" => "Credenciais inválidas!"]);
                }

        }

    }
?>
