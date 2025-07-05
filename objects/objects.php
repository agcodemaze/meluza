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
            if (!$this->pdo) {
                $this->conexao();
            }

            // Prepara a consulta SQL para verificar o usuário
            $sql = "SELECT * FROM USU_USUARIO WHERE USU_DCEMAIL = :USU_DCEMAIL";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':USU_DCEMAIL', $USU_DCEMAIL, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($USU_DCSENHA, $user['USU_DCSENHA'])) 
            {
                return json_encode(["success" => true, "message" => "Credenciais válidas!", "userinfo" => $user]);
            }
            else
                {                
                    return json_encode(["success" => false, "message" => "Credenciais inválidas!"]);
                }
        }

        public function notifyUsuarioEmail($SUBJECT, $MSG, $EMAIL, $anexo = "na")
        {     
            $mail = new PHPMailer(true);
        
            try {
       
                $mail->isSMTP();
                $mail->Host = $_ENV['ENV_SMTP_HOST']; 
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['ENV_SMTP_USER']; 
                $mail->Password = $_ENV['ENV_SMTP_PASS']; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
                $mail->Port = $_ENV['ENV_SMTP_PORT']; 
        
                $mail->CharSet = 'UTF-8';
        
                $mail->setFrom($_ENV['ENV_SMTP_USER'], "Suporte Codemaze");
                $mail->addAddress($EMAIL);             
        
                $mail->isHTML(true);
                $mail->Subject = $SUBJECT;
                $mail->Body    = $MSG;
                $mail->AltBody = $MSG;
        
                $mail->send();
    
                return 'Um link de recuperação de senha foi enviado para o seu e-mail.';
                
            } catch (Exception $e) {
                echo "Erro ao tentar enviar: " . $e->getMessage();
                echo "<br>PHPMailer Error: " . $mail->ErrorInfo;
        
                return "Erro ao enviar e-mail: " . $e->getMessage() . " - " . $mail->ErrorInfo;
            }
        }

        public function insertUserInfo($USU_DCEMAIL, $USU_DCNOME, $USU_DCSENHA)
        {       
            if (!$this->pdo) {
                $this->conexao();
            } 

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO USU_USUARIO 
                        (USU_DCEMAIL, USU_DCNOME, USU_DCSENHA) 
                        VALUES (:USU_DCEMAIL, :USU_DCNOME, :USU_DCSENHA)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':USU_DCEMAIL', $USU_DCEMAIL, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCSENHA', $USU_DCSENHA, PDO::PARAM_STR);
                
                $stmt->execute();   
                
                $response = array("success" => true, "message" => "Usuário cadastrado com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                return $e->getMessage();   
            }            
        }

        function getClienteInfo($USU_IDUSUARIO) 
        {
            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT * FROM CLI_CLIENTE WHERE USU_IDUSUARIO = :USU_IDUSUARIO ORDER BY CLI_DCNOME ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>
