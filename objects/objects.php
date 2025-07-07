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

            $sql = "SELECT * FROM CLI_CLIENTE WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND CLI_STATIVO = 'ATIVO' ORDER BY CLI_DCNOME ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function getTiposLocalInfo() 
        {
            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT * FROM TLO_TIPOLOCAL ORDER BY PLO_DCNOME ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function getClienteInfoById($CLI_IDCLIENTE, $USU_IDUSUARIO) 
        {
            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT * FROM CLI_CLIENTE WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND CLI_IDCLIENTE = :CLI_IDCLIENTE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':CLI_IDCLIENTE', $CLI_IDCLIENTE, PDO::PARAM_STR);
            $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function insertClienteInfo($CLI_DCNOME, $CLI_DCOBS, $CLI_DCTELEFONE, $CLI_DCCEP, $CLI_DCENDERECO, $CLI_DCNUM_ENDERECO, $CLI_DCBAIRRO, $CLI_DCCIDADE, $CLI_DCESTADO, $CLI_DCCOMPLEMENTO, $USU_IDUSUARIO)
        {       
            if (!$this->pdo) {
                $this->conexao();
            } 

            $now = new DateTime(); 
            $CLI_DTULTIMA_ATUALIZACAO = $now->format('Y-m-d H:i:s');
            $CLI_DTCADASTRO = $now->format('Y-m-d H:i:s');
            $CLI_STATIVO = "ATIVO";

            try {
                $sql = "INSERT INTO CLI_CLIENTE 
                        (CLI_DCNOME, CLI_DCOBS, CLI_DCTELEFONE, CLI_DCCEP, CLI_DCENDERECO, CLI_DCNUM_ENDERECO, CLI_DCBAIRRO, CLI_DCCIDADE, CLI_DCESTADO, CLI_DCCOMPLEMENTO, CLI_DTULTIMA_ATUALIZACAO, CLI_DTCADASTRO, CLI_STATIVO, USU_IDUSUARIO) 
                        VALUES (:CLI_DCNOME, CLI_DCOBS, :CLI_DCTELEFONE, :CLI_DCCEP, :CLI_DCENDERECO, :CLI_DCNUM_ENDERECO, :CLI_DCBAIRRO, :CLI_DCCIDADE, :CLI_DCESTADO, :CLI_DCCOMPLEMENTO, :CLI_DTULTIMA_ATUALIZACAO, :CLI_DTCADASTRO, :CLI_STATIVO, :USU_IDUSUARIO)";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':CLI_DCNOME', $CLI_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCTELEFONE', $CLI_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCCEP', $CLI_DCCEP, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCENDERECO', $CLI_DCENDERECO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCNUM_ENDERECO', $CLI_DCNUM_ENDERECO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCBAIRRO', $CLI_DCBAIRRO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCCIDADE', $CLI_DCCIDADE, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCESTADO', $CLI_DCESTADO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCCOMPLEMENTO', $CLI_DCCOMPLEMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DTULTIMA_ATUALIZACAO', $CLI_DTULTIMA_ATUALIZACAO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DTCADASTRO', $CLI_DTCADASTRO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_STATIVO', $CLI_STATIVO, PDO::PARAM_STR); 
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                
                $stmt->execute();   
                
                $response = array("success" => true, "message" => "Cliente cadastrado com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $error =  $e->getMessage();   
                $response = array("success" => false, "message" => "Houve um erro: $error");
                return json_encode($response);
            }            
        }

        public function deleteClienteById($CLI_IDCLIENTE, $CLI_DCNOME)
        {       
            if (!$this->pdo) {
                $this->conexao();
            } 

            $now = new DateTime(); 
            $CLI_DTULTIMA_ATUALIZACAO = $now->format('Y-m-d H:i:s');
            $CLI_STATIVO = "INATIVO";

            try {
                $sql = "UPDATE CLI_CLIENTE 
                        SET CLI_STATIVO = :CLI_STATIVO,
                            CLI_DTULTIMA_ATUALIZACAO = :CLI_DTULTIMA_ATUALIZACAO
                        WHERE CLI_IDCLIENTE = :CLI_IDCLIENTE";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':CLI_IDCLIENTE', $CLI_IDCLIENTE, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DTULTIMA_ATUALIZACAO', $CLI_DTULTIMA_ATUALIZACAO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_STATIVO', $CLI_STATIVO, PDO::PARAM_STR);

                $stmt->execute();   
                
                $response = array("success" => true, "message" => "O(a) cliente $CLI_DCNOME foi apagado com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $error =  $e->getMessage();   
                $response = array("success" => false, "message" => "Houve um erro: $error");
                return json_encode($response);
            }            
        }

        public function updateClienteInfo($CLI_IDCLIENTE, $CLI_DCNOME, $CLI_DCOBS, $CLI_DCTELEFONE, $CLI_DCCEP, $CLI_DCENDERECO, $CLI_DCNUM_ENDERECO, $CLI_DCBAIRRO, $CLI_DCCIDADE, $CLI_DCESTADO, $CLI_DCCOMPLEMENTO, $USU_IDUSUARIO)
        {
            if (!$this->pdo) {
                $this->conexao();
            }
        
            $now = new DateTime();
            $CLI_DTULTIMA_ATUALIZACAO = $now->format('Y-m-d H:i:s');
        
            try {
                $sql = "UPDATE CLI_CLIENTE SET 
                            CLI_DCNOME = :CLI_DCNOME,
                            CLI_DCOBS = :CLI_DCOBS,
                            CLI_DCTELEFONE = :CLI_DCTELEFONE,
                            CLI_DCCEP = :CLI_DCCEP,
                            CLI_DCENDERECO = :CLI_DCENDERECO,
                            CLI_DCNUM_ENDERECO = :CLI_DCNUM_ENDERECO,
                            CLI_DCBAIRRO = :CLI_DCBAIRRO,
                            CLI_DCCIDADE = :CLI_DCCIDADE,
                            CLI_DCESTADO = :CLI_DCESTADO,
                            CLI_DCCOMPLEMENTO = :CLI_DCCOMPLEMENTO,
                            CLI_DTULTIMA_ATUALIZACAO = :CLI_DTULTIMA_ATUALIZACAO,
                            USU_IDUSUARIO = :USU_IDUSUARIO
                        WHERE CLI_IDCLIENTE = :CLI_IDCLIENTE AND USU_IDUSUARIO = :USU_IDUSUARIO";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':CLI_DCNOME', $CLI_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCOBS', $CLI_DCOBS, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCTELEFONE', $CLI_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCCEP', $CLI_DCCEP, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCENDERECO', $CLI_DCENDERECO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCNUM_ENDERECO', $CLI_DCNUM_ENDERECO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCBAIRRO', $CLI_DCBAIRRO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCCIDADE', $CLI_DCCIDADE, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCESTADO', $CLI_DCESTADO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DCCOMPLEMENTO', $CLI_DCCOMPLEMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':CLI_DTULTIMA_ATUALIZACAO', $CLI_DTULTIMA_ATUALIZACAO, PDO::PARAM_STR);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_INT);
                $stmt->bindParam(':CLI_IDCLIENTE', $CLI_IDCLIENTE, PDO::PARAM_INT);
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Cliente atualizado com sucesso.");
                return json_encode($response);
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $response = array("success" => false, "message" => "Erro ao atualizar cliente: $error");
                return json_encode($response);
            }
        }

        public function inserFaxinaInfo($CLI_IDCLIENTE, $FXA_DCTIPO, $FXA_DCDURACAO_ESTIMADA, $FXA_NMPRECO_COMBINADO, $FXA_DTDATA, $FXA_DCOBS)
        {       
            if (!$this->pdo) {
                $this->conexao();
            } 

            // Converte para o formato MySQL
            $dataObj = DateTime::createFromFormat('d/m/Y H:i', $FXA_DTDATA);
            if ($dataObj) {
                $FXA_DTDATA = $dataObj->format('Y-m-d H:i:s');
            } 

            $now = new DateTime(); 
            $FXA_DTDATA_CADASTRO = $now->format('Y-m-d H:i:s');
            $FXA_DTULTIMAATUALIZACAO = $now->format('Y-m-d H:i:s');
            $FXA_STATIVO = "ATIVO";
            $FXA_STSTATUS = "PROGRAMADA";
            $FXA_NMPRECO_COMBINADO = str_replace(['R$', '.', ','], ['', '', '.'], $FXA_NMPRECO_COMBINADO);

            try {
                $sql = "INSERT INTO FXA_FAXINA 
                        (CLI_IDCLIENTE, FXA_DCTIPO, FXA_DCDURACAO_ESTIMADA, FXA_NMPRECO_COMBINADO, FXA_DTDATA, FXA_DCOBS, FXA_DTULTIMAATUALIZACAO, FXA_STATIVO, FXA_DTDATA_CADASTRO, FXA_STSTATUS) 
                        VALUES (:CLI_IDCLIENTE, :FXA_DCTIPO, :FXA_DCDURACAO_ESTIMADA, :FXA_NMPRECO_COMBINADO, :FXA_DTDATA, :FXA_DCOBS, :FXA_DTULTIMAATUALIZACAO, :FXA_STATIVO, :FXA_DTDATA_CADASTRO, :FXA_STSTATUS)";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':CLI_IDCLIENTE', $CLI_IDCLIENTE, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_DCDURACAO_ESTIMADA', $FXA_DCDURACAO_ESTIMADA, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_NMPRECO_COMBINADO', $FXA_NMPRECO_COMBINADO, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_DTDATA', $FXA_DTDATA, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_DCOBS', $FXA_DCOBS, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_DTULTIMAATUALIZACAO', $FXA_DTULTIMAATUALIZACAO, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_STATIVO', $FXA_STATIVO, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_DTDATA_CADASTRO', $FXA_DTDATA_CADASTRO, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_DCTIPO', $FXA_DCTIPO, PDO::PARAM_STR);
                $stmt->bindParam(':FXA_STSTATUS', $FXA_STSTATUS, PDO::PARAM_STR);
               
                $stmt->execute();   
                
                $response = array("success" => true, "message" => "Faxina agendada com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $error =  $e->getMessage();   
                $response = array("success" => false, "message" => "Houve um erro: $error");
                return json_encode($response);
            }            
        }

        function getFaxinasInfo($USU_IDUSUARIO) 
        {
            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT * FROM VW_FAXINA_CLIENTE WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND CLI_STATIVO = 'ATIVO' ORDER BY FXA_DTDATA ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>
