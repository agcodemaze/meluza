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

        function sendPush($title, $message) 
        {
            $content = array(
                "pt" => $message
            );

            /*
            $fields = array(
                'app_id' => "63abf18f-ca40-4c73-bf6f-da330653051d",
                'included_segments' => ['Total Subscriptions'],
                'headings' => array("en" => $title),
                'contents' => $content
            );
            */
            $fields = array(
                'app_id' => "63abf18f-ca40-4c73-bf6f-da330653051d",
                'included_segments' => ['Total Subscriptions'],
                'headings' => array("en" => $title),
                'contents' => $content,
                'web_buttons' => array(
                    array(
                        "id" => "visit-site",
                        "text" => "Clique aqui para retirar.",
                        "icon" => "https://cliente.condomaze.com.br/img/CONDOMAZE_ICO_160.png", 
                        "url" => "https://cliente.condomaze.com.br/"
                    )
                )
            );

            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic os_v2_app_mov7dd6kibghhp3p3izqmuyfdvc5xi5x3a4umvv3v3p2js4ukkfeu7fqnet3mmzm3iaefeko527dwyghkav7gfbzvutwhy5sp62bzni'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            return [
                'success' => ($httpCode === 200),
                'http_code' => $httpCode,
                'response' => json_decode($response, true),
                'error' => $error
            ];        
        }

        function sendPushEncomendaByUserId($title,$message,$externalUserId,$metodo) 
        {
            $content = array(
                "en" => $message
            );

            $condominioIdLink = $this->BANCODEDADOS_CONDOMINIO;

            if($metodo != "ENTREGUE")
            {
                $UrlRedir = "https://cliente.condomaze.com.br/api_encomenda?hash=$metodo&id=$condominioIdLink";
                $text = "Clique aqui para retirar.";
            }
            else
                {
                    $UrlRedir = "https://cliente.condomaze.com.br/inicial";
                    $text = "Condomaze";
                }

            $externalUserIdsArray = array_map(
                fn($id) => trim($id, '" '),
                explode(',', $externalUserId)
            );
       
            $data = [
                "app_id" => "63abf18f-ca40-4c73-bf6f-da330653051d",
                "headings" => [
                    "pt" => $title,
                    "en" => $title
                ],
                "contents" => [
                    "pt" => $message,
                    "en" => $message
                ],
                "url" => "$UrlRedir",
                "web_buttons" => [
                    [
                        "id" => "visit-site",
                        "text" => "$text",
                        "icon" => "https://cliente.condomaze.com.br/img/CONDOMAZE_ICO_160.png",
                        "url" => "$UrlRedir"
                    ]
                ],
                "include_aliases" => [
                    "external_id" => $externalUserIdsArray
                ],
                "target_channel" => "push"
            ];

        
            $fields = json_encode($data);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic os_v2_app_mov7dd6kibghhp3p3izqmuyfdvc5xi5x3a4umvv3v3p2js4ukkfeu7fqnet3mmzm3iaefeko527dwyghkav7gfbzvutwhy5sp62bzni'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
        
            return [
                'success' => ($httpCode === 200),
                'http_code' => $httpCode,
                'response' => json_decode($response, true),
                'error' => $error
            ];        
        }

        function sendPushByUserId($title,$message,$externalUserId,$metodo) 
        {
            $content = array(
                "en" => $message
            );

            $condominioIdLink = $this->BANCODEDADOS_CONDOMINIO;

            if($metodo == "VISITANTE ENTRADA")
            {
                    $UrlRedir = "https://cliente.condomaze.com.br/inicial";
                    $text = "Condomaze";
            }
            if($metodo == "VISITANTE PRECADASTRO")
            {
                    $UrlRedir = "https://cliente.condomaze.com.br/inicial";
                    $text = "Condomaze";
            }

            $externalUserIdsArray = array_map(
                fn($id) => trim($id, '" '),
                explode(',', $externalUserId)
            );
       
            $data = [
                "app_id" => "63abf18f-ca40-4c73-bf6f-da330653051d",
                "headings" => [
                    "pt" => $title,
                    "en" => $title
                ],
                "contents" => [
                    "pt" => $message,
                    "en" => $message
                ],
                "url" => "$UrlRedir",
                "web_buttons" => [
                    [
                        "id" => "visit-site",
                        "text" => "$text",
                        "icon" => "https://cliente.condomaze.com.br/img/CONDOMAZE_ICO_160.png",
                        "url" => "$UrlRedir"
                    ]
                ],
                "include_aliases" => [
                    "external_id" => $externalUserIdsArray
                ],
                "target_channel" => "push"
            ];

        
            $fields = json_encode($data);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic os_v2_app_mov7dd6kibghhp3p3izqmuyfdvc5xi5x3a4umvv3v3p2js4ukkfeu7fqnet3mmzm3iaefeko527dwyghkav7gfbzvutwhy5sp62bzni'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
        
            return [
                'success' => ($httpCode === 200),
                'http_code' => $httpCode,
                'response' => json_decode($response, true),
                'error' => $error
            ];        
        }

        function sendPushToSuport($title,$message,$externalUserId) 
        {
            $content = array(
                "en" => $message
            );

            $UrlRedir = "https://cliente.condomaze.com.br/inicial";
            $text = "Condomaze";         
       
            $data = [
                "app_id" => "63abf18f-ca40-4c73-bf6f-da330653051d",
                "headings" => [
                    "pt" => $title,
                    "en" => $title
                ],
                "contents" => [
                    "pt" => $message,
                    "en" => $message
                ],
                "url" => "$UrlRedir",
                "web_buttons" => [
                    [
                        "id" => "visit-site",
                        "text" => "$text",
                        "icon" => "https://cliente.condomaze.com.br/img/CONDOMAZE_ICO_160.png",
                        "url" => "$UrlRedir"
                    ]
                ],
                "include_aliases" => [
                    "external_id" => trim($externalUserId, '" ')
                ],
                "target_channel" => "push"
            ];

        
            $fields = json_encode($data);
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic os_v2_app_mov7dd6kibghhp3p3izqmuyfdvc5xi5x3a4umvv3v3p2js4ukkfeu7fqnet3mmzm3iaefeko527dwyghkav7gfbzvutwhy5sp62bzni'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
        
            return [
                'success' => ($httpCode === 200),
                'http_code' => $httpCode,
                'response' => json_decode($response, true),
                'error' => $error
            ];        
        }

        function checkFuncSis($CUS_DCFUNCAO, $FUS_DCNAME) 
        {
            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT * 
                    FROM ADM_FUS_FUNCOES_SISTEMAS FUS
                    WHERE CUS_DCFUNCAO = :CUS_DCFUNCAO AND FUS_DCNAME = :FUS_DCNAME";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':CUS_DCFUNCAO', $CUS_DCFUNCAO, PDO::PARAM_STR);
            $stmt->bindParam(':FUS_DCNAME', $FUS_DCNAME, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function checkPlanoContratado($PLA_IDPLANO, $PLU_DCFUNCAO) 
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $sql = "SELECT * 
                    FROM PLU_PLANO_AUTH
                    WHERE PLA_IDPLANO = :PLA_IDPLANO AND PLU_DCFUNCAO = :PLU_DCFUNCAO";
            $stmt = $this->pdoSistema->prepare($sql);
            $stmt->bindParam(':PLA_IDPLANO', $PLA_IDPLANO, PDO::PARAM_STR);
            $stmt->bindParam(':PLU_DCFUNCAO', $PLU_DCFUNCAO, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function checkPlanoContratadoMenu($PLA_IDPLANO) 
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $sql = "SELECT * 
                    FROM PLU_PLANO_AUTH
                    WHERE PLA_IDPLANO = :PLA_IDPLANO";
            $stmt = $this->pdoSistema->prepare($sql);
            $stmt->bindParam(':PLA_IDPLANO', $PLA_IDPLANO, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function checkFuncSisMenu($CUS_DCFUNCAO) 
        {
            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT FUS_DCNAME 
                    FROM ADM_FUS_FUNCOES_SISTEMAS FUS
                    WHERE CUS_DCFUNCAO = :CUS_DCFUNCAO AND FUS_DCVIEW = '1'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':CUS_DCFUNCAO', $CUS_DCFUNCAO, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function getUserInfo($USU_IDUSUARIO) 
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            // Prepara a consulta SQL para verificar o usuário
            $sql = "SELECT * FROM USU_USUARIO WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
            $stmt = $this->pdoSistema->prepare($sql);
            $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        function getCondominiosByUserId($USU_IDUSUARIO)
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $sql = "SELECT * FROM VW_USU_CONDOMINIO WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
            $stmt = $this->pdoSistema->prepare($sql);
            $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;

        }

        function getCondominiosInfoById($CONDOMINIO_ARRAY)
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }
        
            $condominiosInfo = [];
        
            foreach ($CONDOMINIO_ARRAY as $condominio) {
                $CON_IDCONDOMINIO = $condominio["CON_IDCONDOMINIO"];
        
                $sql = "SELECT * FROM CON_CONDOMINIO WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO";
                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                if (!empty($result)) {
                    $condominiosInfo[] = [
                        "condominio" => $result[0], // Pegamos o primeiro resultado, assumindo que o ID é único
                        "usuario" => $condominio
                    ];
                }
            }
        
            return $condominiosInfo;
        }

        function whatsappApiSendMessage($msg, $telefone, $target = "telefone")
        {
            if(!$this->pdo){$this->conexao();}
            
            $this->getParameterInfo();  
            foreach ($this->ARRAY_PARAMETERINFO as $item) {
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_TOKEN') {
                    $token = $item['CFG_DCVALOR']; 
                }
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_INSTANCIA') {
                    $instancia = $item['CFG_DCVALOR']; 
                }
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_ENDPOINT') {
                    $endpoint = $item['CFG_DCVALOR']; 
                }
            } 

            if($target == "telefone") 
            {
                $telefoneDestino = "55$telefone";
            }
            if($target == "grupo") 
            {
                $telefoneDestino = "$telefone";
            }
            
            $url = "$endpoint/message/sendText/$instancia";

            $headers = [
                        "apikey: $token",
                        "Content-Type: application/json"
                        ];

            $data = [
                "number" => "$telefoneDestino",
                "text" => "$msg"
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }

        function whatsappApiSendMessageSuporte($msg)
        {
            if(!$this->pdoSistema){$this->conexaoSistema();}
            
            $this->getParameterAdminInfo();  
            foreach ($this->ARRAY_PARAMETERADMININFO as $item) {
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_TOKEN_SUPORTE') {
                    $token = $item['CFG_DCVALOR']; 
                }
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_INSTANCIA_SUPORTE') {
                    $instancia = $item['CFG_DCVALOR']; 
                }
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_ENDPOINT_SUPORTE') {
                    $endpoint = $item['CFG_DCVALOR']; 
                }
                if ($item['CFG_DCPARAMETRO'] == 'WHATSAPP_TELEFONE_SUPORTE') {
                    $telefone = $item['CFG_DCVALOR']; 
                }
            } 

            $telefoneDestino = "55$telefone";
            
            $url = "$endpoint/message/sendText/$instancia";

            $headers = [
                        "apikey: $token",
                        "Content-Type: application/json"
                        ];

            $data = [
                "number" => "$telefoneDestino",
                "text" => "$msg"
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

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

        public function sendPropostaEmail($SUBJECT, $MSG, $EMAIL)
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
        
                $mail->setFrom($_ENV['ENV_SMTP_USER'], "Condomaze");
                $mail->addAddress($EMAIL);             
        
                $mail->isHTML(true);
                $mail->Subject = $SUBJECT;
                $mail->Body    = $MSG;
                $mail->AltBody = $MSG;
        
                $mail->send();
    
                return 'enviado';
                
            } catch (Exception $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao enviar a solicitação de orçamento para o email $EMAIL: $erro");        
                return 'erro';
            }
        }
             
        public function getAvaliacoesByPrestador($PDS_IDPRESTADOR_SERVICO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_AVALIACAO_PRESTADOR WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO  ORDER BY APS_DTAVAL DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getCategoriasPublicidadeInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM PUC_PUBLICIDADE_CATEGORIA ORDER BY PUC_DCNOME ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()]; 
            }       
        }

        public function getAllPrestadores()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_PUBLICIDADE 
                WHERE EXCLUIDO != '1' OR EXCLUIDO IS NULL
                ORDER BY PDS_DCNOME_PRESTADOR ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getPrestadoresById($PDS_IDPRESTADOR_SERVICO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_PUBLICIDADE 
                WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO AND (EXCLUIDO != '1' OR EXCLUIDO IS NULL)
                ORDER BY PDS_DCNOME_PRESTADOR ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getAvaliacoesByCategoria($PUC_DCNOME)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_PUBLICIDADE WHERE PUC_DCNOME = :PUC_DCNOME AND PDS_STSTATUS = 'PUB' AND (EXCLUIDO != '1' OR EXCLUIDO IS NULL)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PUC_DCNOME', $PUC_DCNOME, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getAvaliacoesNotasAVGByPrestador($PDS_IDPRESTADOR_SERVICO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT AVG(APS_NMNOTA) as AVG FROM VW_AVALIACAO_PRESTADOR WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        // Função para gerar um novo token de acesso
        public function gerarToken($userId) {
            $chaveSecreta = "mcodemaze!4795condominio$#@!!@#$"; // chave secreta forte
            $dados = [
                "user_id" => $userId,
                "exp" => time() + (30 * 24 * 60 * 60) // Expira em 30 dias
            ];
            return base64_encode(json_encode($dados) . "." . hash_hmac('sha256', json_encode($dados), $chaveSecreta));
        }

        public function stmtToArray($stmtFunction)
		{		
			$stmtFunction_array = array();							
			while ($row = $stmtFunction->fetch(PDO::FETCH_ASSOC))
			{	
				array_push($stmtFunction_array, $row);	
			}		
	
			return $stmtFunction_array;
		}	

        public function getEncomendaMoradorInfo($ENC_DCBLOCO, $ENC_DCAPARTAMENTO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT ENC.ENC_IDENCOMENDA, ENC.ENC_DCAPARTAMENTO,  ENC.ENC_DCBLOCO, ENC.ENC_STENCOMENDA, ENC.ENC_DTENTREGA_PORTARIA, ENC.ENC_DTENTREGA_MORADOR, ENC.ENC_DCOBSERVACAO, ENC.ENC_STENTREGA_MORADOR, ENC.ENC_DCFOTO
                        FROM ENC_ENCOMENDA ENC 
                        WHERE ENC.ENC_STENCOMENDA = 'DISPONIVEL' AND ENC.ENC_DCAPARTAMENTO = :ENC_DCAPARTAMENTO AND ENC.ENC_DCBLOCO = :ENC_DCBLOCO AND (ENC.ENC_STENTREGA_MORADOR != 'ENTREGUE' OR ENC.ENC_STENTREGA_MORADOR IS NULL)
                        ORDER BY ENC_DTENTREGA_PORTARIA DESC
                        LIMIT 20";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_DCBLOCO', $ENC_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_DCAPARTAMENTO', $ENC_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_ENCOMENDAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()]; 
            }       
        }

        public function getTokenInfo($USU_DCTOKEN)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT USU_DCTOKEN FROM USU_USUARIO WHERE USU_DCTOKEN = :USU_DCTOKEN";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_DCTOKEN', $USU_DCTOKEN, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_TOKENINFO = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getPubInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_PUBLICIDADE WHERE EXCLUIDO != '1' OR EXCLUIDO IS NULL";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_PUBINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getPubInfoById($PDS_IDPRESTADOR_SERVICO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_PUBLICIDADE WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO AND (EXCLUIDO != '1' OR EXCLUIDO IS NULL)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getEncomendaPortariaInfo($CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT ENC.ENC_IDENCOMENDA, ENC.ENC_DCMODO_ENTREGA, VUSU.USU_DCEMAIL, VUSU.USU_DCNOME, VUSU.USU_DCTELEFONE, ENC.ENC_DCAPARTAMENTO, ENC.ENC_DCBLOCO, ENC.ENC_STENCOMENDA, ENC.ENC_DTENTREGA_PORTARIA, ENC.ENC_DTENTREGA_MORADOR, ENC.ENC_DCOBSERVACAO, ENC.ENC_STENTREGA_MORADOR, ENC.ENC_DCHASHENTREGA, ENC.ENC_DCFOTO
                        FROM ENC_ENCOMENDA ENC 
                        INNER JOIN condomaze.VW_USU_USUARIO_APARTAMENTO VUSU ON (VUSU.CUS_DCAPARTAMENTO = ENC.ENC_DCAPARTAMENTO)
                        WHERE (ENC.ENC_STENTREGA_MORADOR <> 'ENTREGUE' OR ENC.ENC_STENTREGA_MORADOR IS NULL) AND (VUSU.CUS_DCFUNCAO = 'PROPRIETARIO/MORADOR' OR VUSU.CUS_DCFUNCAO = 'INQUILINO' OR VUSU.CUS_DCFUNCAO = 'SUPORTE' OR VUSU.CUS_DCFUNCAO = 'SINDICO')
                        AND VUSU.CON_IDCONDOMINIO = :CON_IDCONDOMINIO
                        ORDER BY ENC_DTENTREGA_PORTARIA DESC
                        LIMIT 100";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_ENCOMENDAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getEncomendaPortariaEntreguesInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT ENC.ENC_IDENCOMENDA, USU.USU_DCNOME, USU.USU_DCTELEFONE, ENC.ENC_DCAPARTAMENTO, ENC.ENC_DCBLOCO, ENC.ENC_STENCOMENDA, ENC.ENC_DTENTREGA_PORTARIA, ENC.ENC_DTENTREGA_MORADOR, ENC.ENC_DCOBSERVACAO, ENC.ENC_STENTREGA_MORADOR, ENC.ENC_DCHASHENTREGA, ENC.ENC_DCFOTO, USU.USU_DCEMAIL
                        FROM ENC_ENCOMENDA ENC 
                        INNER JOIN condomaze.VW_USU_USUARIO_APARTAMENTO USU ON (USU.CUS_DCAPARTAMENTO = ENC.ENC_DCAPARTAMENTO AND USU.CUS_DCBLOCO = ENC.ENC_DCBLOCO)
                        WHERE ENC.ENC_STENTREGA_MORADOR = 'ENTREGUE'
                        ORDER BY ENC_DTENTREGA_PORTARIA DESC
                        ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_ENCOMENDAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getEncomendaPortariaInfoByDate($DATA_INI, $DATA_FIM)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                        FROM ENC_ENCOMENDA                         
                        WHERE ENC_DTENTREGA_MORADOR BETWEEN :DATA_INI AND :DATA_FIM
                        ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':DATA_INI', $DATA_INI, PDO::PARAM_STR);
                $stmt->bindParam(':DATA_FIM', $DATA_FIM, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao buscar os dados de encomenda. Verifique a falha no dashboard administrativo.");
            }       
        }

        public function getPendenciasInfoByDate($DATA_INI, $DATA_FIM)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                        FROM EPE_EVOLUCAO_PENDENCIA                         
                        WHERE EPE_DTLASTUPDATE BETWEEN :DATA_INI AND :DATA_FIM
                        ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':DATA_INI', $DATA_INI, PDO::PARAM_STR);
                $stmt->bindParam(':DATA_FIM', $DATA_FIM, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao buscar os dados de pendencias. Verifique a falha no dashboard administrativo.");
            }       
        }

        public function getPropostasInfoByDate($DATA_INI, $DATA_FIM)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                        FROM ADM_PRO_PROPOSTAS                         
                        WHERE PRO_DTSOLICITACAO BETWEEN :DATA_INI AND :DATA_FIM
                        ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':DATA_INI', $DATA_INI, PDO::PARAM_STR);
                $stmt->bindParam(':DATA_FIM', $DATA_FIM, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao buscar os dados de propostas. Verifique a falha no dashboard administrativo.");
            }       
        }

        public function getAnunciantesInfoByDate()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();} 
            
            try{           
                $sql = "SELECT *
                        FROM VW_PUBLICIDADE WHERE (EXCLUIDO != '1' OR EXCLUIDO IS NULL)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao buscar os dados de publicidade. Verifique a falha no dashboard administrativo.");
            }       
        }

        public function getAnunciantesInfoByDateById($USU_IDUSUARIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                        FROM VW_PUBLICIDADE WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND (EXCLUIDO != '1' OR EXCLUIDO IS NULL)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao buscar os dados de publicidade. Verifique a falha no dashboard administrativo.");
            }       
        }


        public function getEncomendaPortariaEntreguesApBlocoInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT ENC.ENC_IDENCOMENDA, ENC.ENC_DCMODO_ENTREGA, ENC.ENC_DCAPARTAMENTO, ENC.ENC_DCBLOCO, ENC.ENC_STENCOMENDA, ENC.ENC_DTENTREGA_PORTARIA, ENC.ENC_DTENTREGA_MORADOR, ENC.ENC_DCOBSERVACAO, ENC.ENC_STENTREGA_MORADOR, ENC.ENC_DCHASHENTREGA, ENC.ENC_DCFOTO
                        FROM ENC_ENCOMENDA ENC                         
                        WHERE ENC.ENC_STENTREGA_MORADOR = 'ENTREGUE'
                        ORDER BY ENC_DTENTREGA_PORTARIA DESC
                        ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_ENCOMENDAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }       
        }

        public function getParameterInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT CFG_DCPARAMETRO, 
                                CFG_DCVALOR
                                FROM CFG_CONFIGURACAO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_PARAMETERINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
               return ["error" => $e->getMessage()];
            }       
        }

        public function getParameterAdminInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT CFG_DCPARAMETRO, 
                                CFG_DCVALOR
                                FROM CFG_CONFIGURACAO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->execute();
                $this->ARRAY_PARAMETERADMININFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
               return ["error" => $e->getMessage()];
            }       
        }

        public function getListaMoradoresInfo($CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_USU_USUARIO_APARTAMENTO
                                WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO 
                                AND (CUS_DCFUNCAO = 'INQUILINO' 
                                OR CUS_DCFUNCAO = 'PROPRIETARIO/MORADOR' 
                                OR CUS_DCFUNCAO = 'PROPRIETARIO' 
                                OR CUS_DCFUNCAO = 'CONSELHEIRO'
                                OR CUS_DCFUNCAO = 'CONSELHEIRO/MORADOR') 
                                ORDER BY USU_DCNOME ASC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LISTAMORADORESINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getListaMoradoresSalaoFestaInfo($CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_USU_USUARIO_APARTAMENTO
                                WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO 
                                AND (CUS_DCFUNCAO = 'INQUILINO' 
                                OR CUS_DCFUNCAO = 'PROPRIETARIO/MORADOR' 
                                OR CUS_DCFUNCAO = 'CONSELHEIRO/MORADOR') 
                                ORDER BY USU_DCNOME ASC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LISTAMORADORESINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getListaFuncionariosInfo($CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_USU_USUARIO_APARTAMENTO
                                WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO 
                                AND CUS_DCFUNCAO != 'SUPORTE' 
                                AND CUS_DCFUNCAO != 'INQUILINO' 
                                AND CUS_DCFUNCAO != 'PROPRIETARIO/MORADOR' 
                                AND CUS_DCFUNCAO != 'CONSELHEIRO/MORADOR'
                                AND CUS_DCFUNCAO != 'PROPRIETARIO'
                                AND CUS_DCFUNCAO != 'PARCEIRO'
                                ORDER BY USU_DCNOME ASC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }           
        }

        public function getListaFuncionariosPortariaInfo($CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_USU_USUARIO_APARTAMENTO
                                WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO 
                                AND CUS_DCFUNCAO = 'PORTARIA' 
                                ORDER BY USU_DCNOME ASC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }           
        }

        public function getPetsInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM PEM_PETMORADOR
                                ORDER BY PEM_DCNOME ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_PETSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getValidPortariaInfo($ipAcessoClient)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT CFG_DCVALOR FROM CFG_CONFIGURACAO WHERE CFG_DCPARAMETRO = 'IP_PORTARIA'";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $ipPortaria = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($ipPortaria['CFG_DCVALOR'] == $ipAcessoClient || $ipPortaria['CFG_DCVALOR'] == "*") {
                    return 1;
                }
                else
                    {
                        return 0;
                    }

            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPetsInfoById($USU_IDUSUARIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM PEM_PETMORADOR
                                WHERE USU_IDUSUARIO = :USU_IDUSUARIO
                                ORDER BY PEM_DCNOME ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_PETSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getUploadedReportInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT DISTINCT
                        sub.MAX_DTINSERT AS CON_DTINSERT, 
                        sub.CON_DCMES_COMPETENCIA_USUARIO, 
                        sub.CON_DCANO_COMPETENCIA_USUARIO 
                    FROM (
                        SELECT 
                            CON_DCMES_COMPETENCIA_USUARIO, 
                            CON_DCANO_COMPETENCIA_USUARIO, 
                            MAX(CON_DTINSERT) AS MAX_DTINSERT
                        FROM CON_CONCILIACAO
                        GROUP BY CON_DCMES_COMPETENCIA_USUARIO, CON_DCANO_COMPETENCIA_USUARIO
                    ) sub
                    ORDER BY sub.CON_DCANO_COMPETENCIA_USUARIO DESC, 
                             FIELD(sub.CON_DCMES_COMPETENCIA_USUARIO, 
                                   'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 
                                   'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_UPLOADREPORTINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getMoradoresByApInfo($CUS_DCBLOCO, $CUS_DCAPARTAMENTO, $CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_USU_USUARIO_APARTAMENTO
                                WHERE CUS_DCAPARTAMENTO = :CUS_DCAPARTAMENTO 
                                AND CUS_DCBLOCO = :CUS_DCBLOCO
                                AND CON_IDCONDOMINIO = :CON_IDCONDOMINIO
                                AND (CUS_DCFUNCAO = 'INQUILINO' OR CUS_DCFUNCAO = 'CONSELHEIRO/MORADOR' OR CUS_DCFUNCAO = 'PROPRIETARIO/MORADOR')";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CUS_DCAPARTAMENTO', $CUS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':CUS_DCBLOCO', $CUS_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LISTAMORADORESINFO = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getListEventById($USU_IDUSUARIO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                    FROM LEV_LISTA_EVENTO LLE
                    INNER JOIN LEU_LISTAEVENTO_USUARIO LEU ON (LEU.USU_IDUSUARIO = LLE.USU_IDUSUARIO)
                    WHERE LLE.USU_IDUSUARIO = :USU_IDUSUARIO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LISTAEVENTOSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getOrcamentosById($MAN_IDMANUTENCAO_ATIVIDADE) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                    FROM ADM_ORC_ORCAMENTO
                    WHERE MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE AND (EXCLUIDO != '1' OR EXCLUIDO IS NULL)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getOrcamentosByIdOrc($ORC_IDORCAMENTO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                    FROM ADM_ORC_ORCAMENTO
                    WHERE ORC_IDORCAMENTO = :ORC_IDORCAMENTO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ORC_IDORCAMENTO', $ORC_IDORCAMENTO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function insertConciliacaoInfo($ARRAY_DADOS)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
            
           // Preparar e executar as inserções no banco de dados
            foreach ($ARRAY_DADOS as $dados) {

                $dados['VALOR'] = $this->formatarValorParaMySQL($dados['VALOR']); 

                // Query de inserção
                $sql = "INSERT INTO CON_CONCILIACAO (CON_DCTIPO, CON_DCMES_COMPETENCIA, CON_DCDESC, CON_NMVALOR, CON_DTINSERT, CON_DCMES_COMPETENCIA_USUARIO, CON_DCANO_COMPETENCIA_USUARIO, CON_DCANO_COMPETENCIA, CON_NMTITULO)
                          VALUES (:tipo, :mes_competencia, :descricao, :valor, :datanow, :mes_competencia_usuario, :ano_competencia_usuario, :ano_competencia, :titulo)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }
            
                $stmt->bindValue(':tipo', $dados['TIPO'], PDO::PARAM_STR);
                $stmt->bindValue(':mes_competencia', $dados['COMPETENCIA MES'], PDO::PARAM_STR);
                $stmt->bindValue(':descricao', $dados['DESCRICAO'], PDO::PARAM_STR);
                $stmt->bindValue(':valor', $dados['VALOR'], PDO::PARAM_STR);
                $stmt->bindValue(':datanow', $dados['DATANOW'], PDO::PARAM_STR);
                $stmt->bindValue(':mes_competencia_usuario', $dados['COMPETENCIA MES USUARIO'], PDO::PARAM_STR);
                $stmt->bindValue(':ano_competencia_usuario', $dados['COMPETENCIA ANO USUARIO'], PDO::PARAM_STR);
                $stmt->bindValue(':ano_competencia', $dados['COMPETENCIA ANO'], PDO::PARAM_STR);
                $stmt->bindValue(':titulo', $dados['TITULO'], PDO::PARAM_STR);
            
                // Executar a consulta
                if (!$stmt->execute()) {
                    //return "Erro ao inserir os dados: " . $stmt->error;
                } else {
                    //return "Registro inserido com sucesso!";
                }
            
            } 

        }

        public function insertPendenciaInfo($EPE_DCTITULO, $EPE_DCEVOL, $EPE_DCOBS)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $EPE_DTLASTUPDATE = $now->format('Y-m-d H:i:s');
            
                // Query de inserção
                $sql = "INSERT INTO EPE_EVOLUCAO_PENDENCIA (EPE_DCTITULO, EPE_DCEVOL, EPE_DCOBS, EPE_DTLASTUPDATE)
                          VALUES (:EPE_DCTITULO, :EPE_DCEVOL, :EPE_DCOBS, :EPE_DTLASTUPDATE)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':EPE_DCTITULO', $EPE_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':EPE_DCEVOL', $EPE_DCEVOL, PDO::PARAM_STR);
                $stmt->bindValue(':EPE_DCOBS', $EPE_DCOBS, PDO::PARAM_STR);   
                $stmt->bindValue(':EPE_DTLASTUPDATE', $EPE_DTLASTUPDATE, PDO::PARAM_STR); 
      

                // Executar a consulta
                if (!$stmt->execute()) {
                    $erro = $stmt->error; 
                    $response = array("success" => false, "message" => "Erro ao inserir os dados. - $erro");
                    return json_encode($response); 
                } else {
                    $response = array("success" => true, "message" => "Registro inserido com sucesso!");
                    return json_encode($response); 
                }
            
        }

        public function insertArtigoInfo($INA_DCTITULO, $INA_DCORDEM, $INA_DCTEXT, $INA_DCFILEURL)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $INA_DTDATA_INSERT = $now->format('Y-m-d H:i:s');
            $INA_STSTATUS = "NÃO PUBLICADO";
            
                // Query de inserção
                $sql = "INSERT INTO INA_INSTRUCOES_ADEQUACOES (INA_DCTITULO, INA_DCORDEM, INA_DCTEXT, INA_STSTATUS, INA_DTDATA_INSERT, INA_DCFILEURL)
                          VALUES (:INA_DCTITULO, :INA_DCORDEM, :INA_DCTEXT, :INA_STSTATUS, :INA_DTDATA_INSERT, :INA_DCFILEURL)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':INA_DCTITULO', $INA_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':INA_DCORDEM', $INA_DCORDEM, PDO::PARAM_STR);
                $stmt->bindValue(':INA_DCTEXT', $INA_DCTEXT, PDO::PARAM_STR);
                $stmt->bindValue(':INA_STSTATUS', $INA_STSTATUS, PDO::PARAM_STR);
                $stmt->bindValue(':INA_DTDATA_INSERT', $INA_DTDATA_INSERT, PDO::PARAM_STR); 
                $stmt->bindValue(':INA_DCFILEURL', $INA_DCFILEURL, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    return "Erro ao inserir os dados: " . $stmt->error;
                } else {
                    return "Registro inserido com sucesso!";
                }
            
        }

        public function insertOSCadInfo($MAN_IDMANUTENCAO_ATIVIDADE, $MAE_DCOBS, $MAE_DTPROGRAMADA, $MAE_STSTATUS, $USU_IDUSUARIO, $FOR_IDFORNECEDOR, $MAE_DCFOTO_ANTES, $MAE_DCFOTO_DURANTE, $MAE_DCFOTO_DEPOIS)
        {      
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $MAE_DTCADASTRO = $now->format('Y-m-d H:i:s');

            $MAE_DTEXECUCAO = ($MAE_STSTATUS == "CONCLUIDA") ? $MAE_DTCADASTRO : null;
            
                // Query de inserção
                $sql = "INSERT INTO ADM_MAE_MANUTENCAO_ATIVIDADE_EXEC (MAE_DTEXECUCAO, MAE_DTCADASTRO, MAN_IDMANUTENCAO_ATIVIDADE, MAE_DCOBS, MAE_DTPROGRAMADA, MAE_STSTATUS, USU_IDUSUARIO, FOR_IDFORNECEDOR, MAE_DCFOTO_ANTES, MAE_DCFOTO_DURANTE, MAE_DCFOTO_DEPOIS)
                          VALUES (:MAE_DTEXECUCAO, :MAE_DTCADASTRO, :MAN_IDMANUTENCAO_ATIVIDADE, :MAE_DCOBS, :MAE_DTPROGRAMADA, :MAE_STSTATUS, :USU_IDUSUARIO, :FOR_IDFORNECEDOR, :MAE_DCFOTO_ANTES, :MAE_DCFOTO_DURANTE, :MAE_DCFOTO_DEPOIS)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':MAE_DTCADASTRO', $MAE_DTCADASTRO, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_DCOBS', $MAE_DCOBS, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_DTPROGRAMADA', $MAE_DTPROGRAMADA, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_STSTATUS', $MAE_STSTATUS, PDO::PARAM_STR); 
                $stmt->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindValue(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_DCFOTO_ANTES', $MAE_DCFOTO_ANTES, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_DCFOTO_DURANTE', $MAE_DCFOTO_DURANTE, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_DCFOTO_DEPOIS', $MAE_DCFOTO_DEPOIS, PDO::PARAM_STR);
                $stmt->bindValue(':MAE_DTEXECUCAO', $MAE_DTEXECUCAO, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    $erro = $stmt->error; 
                    $response = array("success" => false, "message" => "Erro ao cadastrar a Ordem de Serviço. - $erro");
                    return json_encode($response); 
                } else {
                    $response = array("success" => true, "message" => "Ordem de Serviço cadastrada com sucesso.");
                    return json_encode($response); 
                }
            
        }

        public function insertGuiaRapidoSistemaInfo($GUR_DCTITULO,$GUR_DCDESC, $GUR_DCURL)
        {      
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $now = new DateTime(); 
            $GUR_DTLASTUPDATE = $now->format('Y-m-d H:i:s');

            
                // Query de inserção
                $sql = "INSERT INTO GUR_GUIA_RAPIDO (GUR_DCTITULO, GUR_DCDESC, GUR_DCURL, GUR_DTLASTUPDATE)
                          VALUES (:GUR_DCTITULO, :GUR_DCDESC, :GUR_DCURL, :GUR_DTLASTUPDATE)";

                // Preparar a consulta
                $stmt = $this->pdoSistema->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':GUR_DCTITULO', $GUR_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':GUR_DCDESC', $GUR_DCDESC, PDO::PARAM_STR);
                $stmt->bindValue(':GUR_DCURL', $GUR_DCURL, PDO::PARAM_STR);
                $stmt->bindValue(':GUR_DTLASTUPDATE', $GUR_DTLASTUPDATE, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    $erro = $stmt->error; 
                    $response = array("success" => false, "message" => "Erro ao cadastrar o Guia Rápido. - $erro");
                    return json_encode($response); 
                } else {
                    $response = array("success" => true, "message" => "Guia Rápido cadastrado com sucesso.");
                    return json_encode($response); 
                }
            
        }

        public function UpdateGuiaRapidoSistemaInfo($GUR_IDGUIA_RAPIDO, $GUR_DCTITULO, $GUR_DCDESC, $GUR_DCURL="")
        {      
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }
        
            $now = new DateTime(); 
            $GUR_DTLASTUPDATE = $now->format('Y-m-d H:i:s');
        
            // Monta dinamicamente os campos a serem atualizados
            $sql = "UPDATE GUR_GUIA_RAPIDO SET 
                        GUR_DCTITULO = :GUR_DCTITULO,
                        GUR_DCDESC = :GUR_DCDESC,
                        GUR_DTLASTUPDATE = :GUR_DTLASTUPDATE";

            // Só atualiza o campo GUR_DCURL se não for vazio
            if (!empty($GUR_DCURL)) {
                $sql .= ", GUR_DCURL = :GUR_DCURL";
            }
        
            $sql .= " WHERE GUR_IDGUIA_RAPIDO = :GUR_IDGUIA_RAPIDO";
        
            $stmt = $this->pdoSistema->prepare($sql);
            if (!$stmt) {
                die("Erro ao preparar a consulta: " . $this->pdoSistema->errorInfo()[2]);
            }
        
            $stmt->bindValue(':GUR_DCTITULO', $GUR_DCTITULO, PDO::PARAM_STR);
            $stmt->bindValue(':GUR_DCDESC', $GUR_DCDESC, PDO::PARAM_STR);
            $stmt->bindValue(':GUR_DTLASTUPDATE', $GUR_DTLASTUPDATE, PDO::PARAM_STR);
            $stmt->bindValue(':GUR_IDGUIA_RAPIDO', $GUR_IDGUIA_RAPIDO, PDO::PARAM_INT);
        
            if (!empty($GUR_DCURL)) {
                $stmt->bindValue(':GUR_DCURL', $GUR_DCURL, PDO::PARAM_STR);
            }
        
            if (!$stmt->execute()) {
                $erro = $stmt->errorInfo()[2]; 
                $response = array("success" => false, "message" => "Erro ao atualizar o Guia Rápido. - $erro");
                return json_encode($response); 
            } else {
                $response = array("success" => true, "message" => "Guia Rápido atualizado com sucesso.");
                return json_encode($response); 
            }
        }

        public function updateOSCadInfo($MAE_IDMANUTENCAO_ATIVIDADE_EXEC, $MAE_DCOBS, $MAE_DTPROGRAMADA, $MAE_STSTATUS, $USU_IDUSUARIO, $FOR_IDFORNECEDOR, $MAE_DCFOTO_ANTES = null, $MAE_DCFOTO_DURANTE = null, $MAE_DCFOTO_DEPOIS = null)
        {
            if (!$this->pdo) {
                $this->conexao();
            }
        
            $now = new DateTime(); 
            $MAE_DTCADASTRO = $now->format('Y-m-d H:i:s');
            $MAE_DTEXECUCAO = ($MAE_STSTATUS == "CONCLUIDA") ? $MAE_DTCADASTRO : null;
        
            // Campos fixos
            $campos = [
                'MAE_DTEXECUCAO = :MAE_DTEXECUCAO',
                'MAE_DTCADASTRO = :MAE_DTCADASTRO',
                'MAE_DCOBS = :MAE_DCOBS',
                'MAE_DTPROGRAMADA = :MAE_DTPROGRAMADA',
                'MAE_STSTATUS = :MAE_STSTATUS',
                'USU_IDUSUARIO = :USU_IDUSUARIO',
                'FOR_IDFORNECEDOR = :FOR_IDFORNECEDOR'
            ];
        
            // Campos de fotos, adicionados só se tiver valor
            $params = [
                ':MAE_DTEXECUCAO' => $MAE_DTEXECUCAO,
                ':MAE_DTCADASTRO' => $MAE_DTCADASTRO,
                ':MAE_DCOBS' => $MAE_DCOBS,
                ':MAE_DTPROGRAMADA' => $MAE_DTPROGRAMADA,
                ':MAE_STSTATUS' => $MAE_STSTATUS,
                ':USU_IDUSUARIO' => $USU_IDUSUARIO,
                ':FOR_IDFORNECEDOR' => $FOR_IDFORNECEDOR
            ];
        
            if (!empty($MAE_DCFOTO_ANTES)) {
                $campos[] = 'MAE_DCFOTO_ANTES = :MAE_DCFOTO_ANTES';
                $params[':MAE_DCFOTO_ANTES'] = $MAE_DCFOTO_ANTES;
            }
        
            if (!empty($MAE_DCFOTO_DURANTE)) {
                $campos[] = 'MAE_DCFOTO_DURANTE = :MAE_DCFOTO_DURANTE';
                $params[':MAE_DCFOTO_DURANTE'] = $MAE_DCFOTO_DURANTE;
            }
        
            if (!empty($MAE_DCFOTO_DEPOIS)) {
                $campos[] = 'MAE_DCFOTO_DEPOIS = :MAE_DCFOTO_DEPOIS';
                $params[':MAE_DCFOTO_DEPOIS'] = $MAE_DCFOTO_DEPOIS;
            }
        
            $sql = "UPDATE ADM_MAE_MANUTENCAO_ATIVIDADE_EXEC SET " . implode(", ", $campos) . " WHERE MAE_IDMANUTENCAO_ATIVIDADE_EXEC = :MAE_IDMANUTENCAO_ATIVIDADE_EXEC";
            $params[':MAE_IDMANUTENCAO_ATIVIDADE_EXEC'] = $MAE_IDMANUTENCAO_ATIVIDADE_EXEC;
        
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                return json_encode(["success" => false, "message" => "Erro ao preparar a consulta."]);
            }
        
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        
            if (!$stmt->execute()) {
                $erro = implode(" | ", $stmt->errorInfo());
                return json_encode(["success" => false, "message" => "Erro ao atualizar a Ordem de Serviço. - $erro"]);
            }
        
            return json_encode(["success" => true, "message" => "Ordem de Serviço atualizada com sucesso."]);
        }


        public function insertTicketSuporteInfo($USU_IDUSUARIO, $SUP_DCTITULO, $SUP_DCDESC, $SUP_DCFOTO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $now = new DateTime(); 
            $SUP_DTCADASTRO = $now->format('Y-m-d H:i:s');
            $SUP_DTLASTUPDATE = $now->format('Y-m-d H:i:s');
            $SUP_DCSTATUS = "ABERTO";
            
                // Query de inserção
                $sql = "INSERT INTO SUP_SUPORTE (SUP_DTCADASTRO, SUP_DTLASTUPDATE, SUP_DCSTATUS, CON_IDCONDOMINIO, USU_IDUSUARIO, SUP_DCTITULO, SUP_DCDESC, SUP_DCFOTO)
                          VALUES (:SUP_DTCADASTRO, :SUP_DTLASTUPDATE, :SUP_DCSTATUS, :CON_IDCONDOMINIO, :USU_IDUSUARIO, :SUP_DCTITULO, :SUP_DCDESC, :SUP_DCFOTO)";

                // Preparar a consulta
                $stmt = $this->pdoSistema->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':SUP_DTCADASTRO', $SUP_DTCADASTRO, PDO::PARAM_STR);
                $stmt->bindValue(':SUP_DTLASTUPDATE', $SUP_DTLASTUPDATE, PDO::PARAM_STR);
                $stmt->bindValue(':SUP_DCSTATUS', $SUP_DCSTATUS, PDO::PARAM_STR);
                $stmt->bindValue(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                $stmt->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                $stmt->bindValue(':SUP_DCTITULO', $SUP_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':SUP_DCDESC', $SUP_DCDESC, PDO::PARAM_STR);
                $stmt->bindValue(':SUP_DCFOTO', $SUP_DCFOTO, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    $erro = $stmt->error; 
                    $response = array("success" => false, "message" => "Houve um erro ao criar o ticket. - $erro");
                    return json_encode($response); 
                } else {
                    $response = array("success" => true, "message" => "Ticket criado com sucesso. Nosso suporte irá entrar em contato por e-mail ou Whatsapp.");
                    return json_encode($response); 
                }
            
        }

        public function insertOrcamentoCadInfo($USU_IDUSUARIO, $MAN_IDMANUTENCAO_ATIVIDADE, $ORC_DCTITULO, $ORC_DCDESC, $ORC_DTPRAZO_RESP, $ORC_DCFOTO1, $ORC_DCFOTO2, $ORC_DCFOTO3, $ORC_DCFOTO4, $ORC_DCFOTO5, $ORC_DCFOTO6, $ORC_DCARQUIVO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $ORC_DTDATA_CAD = $now->format('Y-m-d H:i:s');
            
                // Query de inserção
                $sql = "INSERT INTO ADM_ORC_ORCAMENTO (ORC_DTDATA_CAD, USU_IDUSUARIO, MAN_IDMANUTENCAO_ATIVIDADE, ORC_DCTITULO, ORC_DCDESC, ORC_DTPRAZO_RESP, ORC_DCFOTO1, ORC_DCFOTO2, ORC_DCFOTO3, ORC_DCFOTO4, ORC_DCFOTO5, ORC_DCFOTO6, ORC_DCARQUIVO)
                          VALUES (:ORC_DTDATA_CAD, :USU_IDUSUARIO, :MAN_IDMANUTENCAO_ATIVIDADE, :ORC_DCTITULO, :ORC_DCDESC, :ORC_DTPRAZO_RESP, :ORC_DCFOTO1, :ORC_DCFOTO2, :ORC_DCFOTO3, :ORC_DCFOTO4, :ORC_DCFOTO5, :ORC_DCFOTO6, :ORC_DCARQUIVO)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':ORC_DTDATA_CAD', $ORC_DTDATA_CAD, PDO::PARAM_STR);
                $stmt->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCTITULO', $ORC_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCDESC', $ORC_DCDESC, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DTPRAZO_RESP', $ORC_DTPRAZO_RESP, PDO::PARAM_STR); 
                $stmt->bindValue(':ORC_DCFOTO1', $ORC_DCFOTO1, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCFOTO2', $ORC_DCFOTO2, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCFOTO3', $ORC_DCFOTO3, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCFOTO4', $ORC_DCFOTO4, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCFOTO5', $ORC_DCFOTO5, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCFOTO6', $ORC_DCFOTO6, PDO::PARAM_STR);
                $stmt->bindValue(':ORC_DCARQUIVO', $ORC_DCARQUIVO, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    $erro = $stmt->error; 
                    $response = array("success" => false, "message" => "Erro ao cadastrar o modelo de orçamento. - $erro");
                    return json_encode($response); 
                } else {
                    $response = array("success" => true, "message" => "Modelo de orçamento cadastrado com sucesso.");
                    return json_encode($response); 
                }
            
        }

        public function updateOrcamentoCadInfo(
            $USU_IDUSUARIO,
            $ORC_IDORCAMENTO,
            $ORC_DCTITULO,
            $ORC_DCDESC,
            $ORC_DTPRAZO_RESP,
            $ORC_DCFOTO1,
            $ORC_DCFOTO2,
            $ORC_DCFOTO3,
            $ORC_DCFOTO4,
            $ORC_DCFOTO5,
            $ORC_DCFOTO6,
            $ORC_DCARQUIVO
        ) {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
        
            $now = new DateTime(); 
            $ORC_DTUPDATE = $now->format('Y-m-d H:i:s');
        
            // Inicia os campos obrigatórios
            $fields = [
                'USU_IDUSUARIO = :USU_IDUSUARIO',
                'ORC_DCTITULO = :ORC_DCTITULO',
                'ORC_DCDESC = :ORC_DCDESC',
                'ORC_DTPRAZO_RESP = :ORC_DTPRAZO_RESP',
                'ORC_DTUPDATE = :ORC_DTUPDATE'
            ];
        
            // Cria array de binds
            $params = [
                ':USU_IDUSUARIO' => $USU_IDUSUARIO,
                ':ORC_DCTITULO' => $ORC_DCTITULO,
                ':ORC_DCDESC' => $ORC_DCDESC,
                ':ORC_DTPRAZO_RESP' => $ORC_DTPRAZO_RESP,
                ':ORC_DTUPDATE' => $ORC_DTUPDATE,
                ':ORC_IDORCAMENTO' => $ORC_IDORCAMENTO
            ];
        
            // Adiciona campos das fotos e arquivo apenas se não estiverem vazios
            if (!empty($ORC_DCFOTO1)) {
                $fields[] = 'ORC_DCFOTO1 = :ORC_DCFOTO1';
                $params[':ORC_DCFOTO1'] = $ORC_DCFOTO1;
            }
            if (!empty($ORC_DCFOTO2)) {
                $fields[] = 'ORC_DCFOTO2 = :ORC_DCFOTO2';
                $params[':ORC_DCFOTO2'] = $ORC_DCFOTO2;
            }
            if (!empty($ORC_DCFOTO3)) {
                $fields[] = 'ORC_DCFOTO3 = :ORC_DCFOTO3';
                $params[':ORC_DCFOTO3'] = $ORC_DCFOTO3;
            }
            if (!empty($ORC_DCFOTO4)) {
                $fields[] = 'ORC_DCFOTO4 = :ORC_DCFOTO4';
                $params[':ORC_DCFOTO4'] = $ORC_DCFOTO4;
            }
            if (!empty($ORC_DCFOTO5)) {
                $fields[] = 'ORC_DCFOTO5 = :ORC_DCFOTO5';
                $params[':ORC_DCFOTO5'] = $ORC_DCFOTO5;
            }
            if (!empty($ORC_DCFOTO6)) {
                $fields[] = 'ORC_DCFOTO6 = :ORC_DCFOTO6';
                $params[':ORC_DCFOTO6'] = $ORC_DCFOTO6;
            }
            if (!empty($ORC_DCARQUIVO)) {
                $fields[] = 'ORC_DCARQUIVO = :ORC_DCARQUIVO';
                $params[':ORC_DCARQUIVO'] = $ORC_DCARQUIVO;
            }
        
            // Monta a query dinamicamente
            $sql = "UPDATE ADM_ORC_ORCAMENTO SET " . implode(', ', $fields) . " WHERE ORC_IDORCAMENTO = :ORC_IDORCAMENTO";
        
            // Preparar a consulta
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                die("Erro ao preparar a consulta: " . $this->pdo->errorInfo()[2]);
            }            
        
            // Executar a consulta
            if (!$stmt->execute($params)) {
                $erro = implode(' | ', $stmt->errorInfo()); 
                $response = array("success" => false, "message" => "Erro ao editar o modelo de orçamento. - $erro");
                return json_encode($response); 
            } else {
                $response = array("success" => true, "message" => "Modelo de orçamento atualizado com sucesso.");
                return json_encode($response); 
            }
        }
        

        public function updateArtigoInfo($INA_DCTITULO, $INA_DCORDEM, $INA_DCTEXT, $INA_DCFILEURL, $INA_IDINSTRUCOES_ADEQUACOES)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }        
            
            if($INA_DCFILEURL == "")
            {
                // Query de inserção
                $sql = "UPDATE  INA_INSTRUCOES_ADEQUACOES SET
                INA_DCTITULO = :INA_DCTITULO,
                INA_DCORDEM = :INA_DCORDEM,
                INA_DCTEXT = :INA_DCTEXT
                WHERE INA_IDINSTRUCOES_ADEQUACOES = :INA_IDINSTRUCOES_ADEQUACOES";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':INA_DCTITULO', $INA_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':INA_DCORDEM', $INA_DCORDEM, PDO::PARAM_STR);
                $stmt->bindValue(':INA_DCTEXT', $INA_DCTEXT, PDO::PARAM_STR);
                $stmt->bindValue(':INA_IDINSTRUCOES_ADEQUACOES', $INA_IDINSTRUCOES_ADEQUACOES, PDO::PARAM_STR);
            }
            else
                {
                    // Query de inserção
                    $sql = "UPDATE  INA_INSTRUCOES_ADEQUACOES SET
                    INA_DCTITULO = :INA_DCTITULO,
                    INA_DCORDEM = :INA_DCORDEM,
                    INA_DCTEXT = :INA_DCTEXT,
                    INA_DCFILEURL = :INA_DCFILEURL
                    WHERE INA_IDINSTRUCOES_ADEQUACOES = :INA_IDINSTRUCOES_ADEQUACOES";

                    // Preparar a consulta
                    $stmt = $this->pdo->prepare($sql);
                    if (!$stmt) {
                        die("Erro ao preparar a consulta: " . $conn->error);
                    }            
                    $stmt->bindValue(':INA_DCTITULO', $INA_DCTITULO, PDO::PARAM_STR);
                    $stmt->bindValue(':INA_DCORDEM', $INA_DCORDEM, PDO::PARAM_STR);
                    $stmt->bindValue(':INA_DCTEXT', $INA_DCTEXT, PDO::PARAM_STR);
                    $stmt->bindValue(':INA_DCFILEURL', $INA_DCFILEURL, PDO::PARAM_STR);
                    $stmt->bindValue(':INA_IDINSTRUCOES_ADEQUACOES', $INA_IDINSTRUCOES_ADEQUACOES, PDO::PARAM_STR);
                }

                // Executar a consulta
                if (!$stmt->execute()) {
                    return "Erro ao atualizar os dados: " . $stmt->error;
                } else {
                    return "Registro atualizado com sucesso!";
                }
            
        }

        public function updateInicialInfo($IFO_DCTITULO, $IFO_DCRESUMO, $IFO_DCDESC)
        {       
          // Verifica se a conexão já foi estabelecida
          if (!$this->pdo) {
            $this->conexao();
        }     

            // Query de inserção
            $sql = "UPDATE  IFO_INICIAL_INFO SET
            IFO_DCTITULO = :IFO_DCTITULO,
            IFO_DCRESUMO = :IFO_DCRESUMO,
            IFO_DCDESC = :IFO_DCDESC";

            // Preparar a consulta
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                die("Erro ao preparar a consulta: " . $conn->error);
            }            
            $stmt->bindValue(':IFO_DCTITULO', $IFO_DCTITULO, PDO::PARAM_STR);
            $stmt->bindValue(':IFO_DCRESUMO', $IFO_DCRESUMO, PDO::PARAM_STR);
            $stmt->bindValue(':IFO_DCDESC', $IFO_DCDESC, PDO::PARAM_STR);

            // Executar a consulta
            if (!$stmt->execute()) {
                return "Erro ao atualizar os dados: " . $stmt->error;
            } else {
                return "Registro atualizado com sucesso!";
            }            
        }

        public function updatePendenciaInfo($EPE_DCTITULO, $EPE_DCEVOL, $EPE_DCOBS, $EPE_IDEVOLUCAO_PENDENCIA)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }                    

                // Query de inserção
                $sql = "UPDATE  EPE_EVOLUCAO_PENDENCIA SET
                EPE_DCTITULO = :EPE_DCTITULO,
                EPE_DCEVOL = :EPE_DCEVOL,
                EPE_DCOBS = :EPE_DCOBS
                WHERE EPE_IDEVOLUCAO_PENDENCIA = :EPE_IDEVOLUCAO_PENDENCIA";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':EPE_DCTITULO', $EPE_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':EPE_DCEVOL', $EPE_DCEVOL, PDO::PARAM_STR);
                $stmt->bindValue(':EPE_DCOBS', $EPE_DCOBS, PDO::PARAM_STR);
                $stmt->bindValue(':EPE_IDEVOLUCAO_PENDENCIA', $EPE_IDEVOLUCAO_PENDENCIA, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    $response = array("success" => false, "message" => "Erro ao atualizar os dados.");
                    return json_encode($response); 
                } else {
                    $response = array("success" => true, "message" => "Registro atualizado com sucesso!");
                    return json_encode($response); 
                }
            
        }

        public function updateVisitanteInfoByVis($VIS_DCDOCUMENTO, $VIS_DCFOTO_VISITANTE, $VIS_IDVISITANTE, $VIS_DCPLACA_VEICULO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }     
            
                $VIS_STCONVITE = "ATUALIZADO PELO VISITANTE";
                $VIS_STSTATUS = "CONFIRMADO";

                // Query de inserção
                $sql = "UPDATE  VIS_VISITANTE SET
                VIS_DCDOCUMENTO = :VIS_DCDOCUMENTO,
                VIS_DCPLACA_VEICULO = :VIS_DCPLACA_VEICULO,
                VIS_DCFOTO_VISITANTE = :VIS_DCFOTO_VISITANTE,
                VIS_STSTATUS = :VIS_STSTATUS,
                VIS_STCONVITE = :VIS_STCONVITE
                WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':VIS_DCDOCUMENTO', $VIS_DCDOCUMENTO, PDO::PARAM_STR);
                $stmt->bindValue(':VIS_DCPLACA_VEICULO', $VIS_DCPLACA_VEICULO, PDO::PARAM_STR);
                $stmt->bindValue(':VIS_DCFOTO_VISITANTE', $VIS_DCFOTO_VISITANTE, PDO::PARAM_STR);
                $stmt->bindValue(':VIS_STCONVITE', $VIS_STCONVITE, PDO::PARAM_STR);
                $stmt->bindValue(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
                $stmt->bindValue(':VIS_STSTATUS', $VIS_STSTATUS, PDO::PARAM_STR);

                // Executar a consulta
                if (!$stmt->execute()) {
                    $error = $stmt->error;
                    $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"O visitante com número de documento $VIS_DCDOCUMENTO não conseguiu confirmar os dados través de convite. $error");
                    return "Erro ao atualizar os dados: " . $stmt->error;
                } else {
                    $this->insertLogInfo("update", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"O visitante com número de documento $VIS_DCDOCUMENTO confirmou os dados no sistema através de convite.");
                    return "Registro atualizado com sucesso!";
                }            
        }

        public function updateVagasVisitante($VGA_IDVAGAS_VISITANTE, $VIS_IDVISITANTE)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }                    
            
            $now = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
            $VGA_DTENTRADA = $now->format('Y-m-d H:i:s');

            $sqlSelect = "SELECT * FROM VW_VISITANTES WHERE VGA_IDVAGAS_VISITANTE = :VGA_IDVAGAS_VISITANTE";
            $stmtSelect = $this->pdo->prepare($sqlSelect);
            $stmtSelect->bindValue(':VGA_IDVAGAS_VISITANTE', $VGA_IDVAGAS_VISITANTE, PDO::PARAM_STR);
            $stmtSelect->execute(); 
            $resultSelect = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($resultSelect) {
                echo json_encode(["status" => "error", "message" => "Já existe um visitante utilizando esta vaga."]);
                exit; 
            }
            
            $sqlDel = "UPDATE VGA_VAGAS_VISITANTE SET VGA_STSTATUS = 'LIVRE', VIS_IDVISITANTE = NULL, VGA_DTENTRADA = NULL WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";
            $stmtDel = $this->pdo->prepare($sqlDel);
            $stmtDel->bindValue(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
            $stmtDel->execute();              
            
            // Query de inserção
            $sql = "UPDATE  VGA_VAGAS_VISITANTE SET
            VGA_IDVAGAS_VISITANTE = :VGA_IDVAGAS_VISITANTE,
            VIS_IDVISITANTE = :VIS_IDVISITANTE,
            VGA_DTENTRADA = :VGA_DTENTRADA
            WHERE VGA_IDVAGAS_VISITANTE = :VGA_IDVAGAS_VISITANTE";
            // Preparar a consulta
            $stmt = $this->pdo->prepare($sql);
            if (!$stmt) {
                die("Erro ao preparar a consulta: " . $conn->error);
            }            
            $stmt->bindValue(':VGA_IDVAGAS_VISITANTE', $VGA_IDVAGAS_VISITANTE, PDO::PARAM_STR);
            $stmt->bindValue(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
            $stmt->bindValue(':VGA_DTENTRADA', $VGA_DTENTRADA, PDO::PARAM_STR);
            // Executar a consulta
            if (!$stmt->execute()) {
                return json_encode(["status" => "error", "message" => "Erro ao definir a vaga."]);
            } else {
                return json_encode(["status" => "success", "message" => "A vaga foi definida com sucesso."]);
            }
            
        }

        public function insertChurrasEventoInfo($USU_IDUSUARIO, $LEU_DCCONVIDADO_HOMEM, $LEU_DCCONVIDADO_MULHER)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
            
                // Query de inserção
                $sql = "INSERT INTO LEU_LISTAEVENTO_USUARIO (USU_IDUSUARIO, LEU_DCCONVIDADO_HOMEM, LEU_DCCONVIDADO_MULHER)
                          VALUES (:USU_IDUSUARIO, :LEU_DCCONVIDADO_HOMEM, :LEU_DCCONVIDADO_MULHER)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindValue(':LEU_DCCONVIDADO_HOMEM', $LEU_DCCONVIDADO_HOMEM, PDO::PARAM_STR);
                $stmt->bindValue(':LEU_DCCONVIDADO_MULHER', $LEU_DCCONVIDADO_MULHER, PDO::PARAM_STR);


            
                // Executar a consulta
                if (!$stmt->execute()) {
                    //return "Erro ao inserir os dados: " . $stmt->error;
                } else {
                    //return "Registro inserido com sucesso!";
                }
            
        }

        public function insertChurrasEventoItensInfo($USU_IDUSUARIO, $LEV_DCPRODUTO, $LEV_DCTIPO, $LEV_DCQTDE, $LEV_DCVALOR, $LEV_DCVALOR_TOTAL)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
            
                // Query de inserção
                $sql = "INSERT INTO LEV_LISTA_EVENTO (USU_IDUSUARIO, LEV_DCPRODUTO, LEV_DCTIPO, LEV_DCQTDE, LEV_DCVALOR, LEV_DCVALOR_TOTAL)
                          VALUES (:USU_IDUSUARIO, :LEV_DCPRODUTO, :LEV_DCTIPO, :LEV_DCQTDE, :LEV_DCVALOR, :LEV_DCVALOR_TOTAL)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }            
                $stmt->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindValue(':LEV_DCPRODUTO', $LEV_DCPRODUTO, PDO::PARAM_STR);
                $stmt->bindValue(':LEV_DCTIPO', $LEV_DCTIPO, PDO::PARAM_STR);
                $stmt->bindValue(':LEV_DCQTDE', $LEV_DCQTDE, PDO::PARAM_STR);
                $stmt->bindValue(':LEV_DCVALOR', $LEV_DCVALOR, PDO::PARAM_STR);
                $stmt->bindValue(':LEV_DCVALOR_TOTAL', $LEV_DCVALOR_TOTAL, PDO::PARAM_STR);


            
                // Executar a consulta
                if (!$stmt->execute()) {
                    //return "Erro ao inserir os dados: " . $stmt->error;
                } else {
                    //return "Registro inserido com sucesso!";
                }
            
        }
        
        public function insertConciliacaoInfoDespesa($ARRAY_DADOS)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
            
           // Preparar e executar as inserções no banco de dados
            foreach ($ARRAY_DADOS as $dados) {

                $dados['VALOR'] = $this->formatarValorParaMySQL($dados['VALOR']); 

                // Query de inserção
                $sql = "INSERT INTO CON_CONCILIACAO (CON_DCTIPO, CON_NMVALOR, CON_DTINSERT, CON_DCMES_COMPETENCIA_USUARIO, CON_DCANO_COMPETENCIA_USUARIO, CON_NMTITULO)
                          VALUES (:tipo, :valor, :datanow, :mes_competencia_usuario, :ano_competencia_usuario, :titulo)";

                // Preparar a consulta
                $stmt = $this->pdo->prepare($sql);
                if (!$stmt) {
                    die("Erro ao preparar a consulta: " . $conn->error);
                }
            
                $stmt->bindValue(':tipo', $dados['TIPO'], PDO::PARAM_STR);
                $stmt->bindValue(':valor', $dados['VALOR'], PDO::PARAM_STR);
                $stmt->bindValue(':datanow', $dados['DATANOW'], PDO::PARAM_STR);
                $stmt->bindValue(':mes_competencia_usuario', $dados['COMPETENCIA MES USUARIO'], PDO::PARAM_STR);
                $stmt->bindValue(':ano_competencia_usuario', $dados['COMPETENCIA ANO USUARIO'], PDO::PARAM_STR);
                $stmt->bindValue(':titulo', $dados['TITULO'], PDO::PARAM_STR);
            
                // Executar a consulta
                if (!$stmt->execute()) {
                    //return "Erro ao inserir os dados: " . $stmt->error;
                } else {
                    //return "Registro inserido com sucesso!";
                }
            
            }

        }

        function formatarValorParaMySQL($valor) {
            // Remove separadores de milhar
            $valor = str_replace(',', '', $valor);
            // Retorna o valor convertido para float
            return (float)$valor;
        }

        public function getListaMensagensSugestoesInfo($USER_EMAIL, $IDCONDOMINIO, $USER_NIVEL)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM REC_RECLAMACAO
                                ORDER BY REC_DTDATA DESC
                                LIMIT 5";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_MENSAGENSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getListaMensagensSugestoesInfoFull($USER_EMAIL, $IDCONDOMINIO, $USER_NIVEL)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM REC_RECLAMACAO
                                ORDER BY REC_DTDATA DESC
                                LIMIT 500";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_MENSAGENSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getArtigosInfoInicial()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * 
                        FROM INA_INSTRUCOES_ADEQUACOES
                        ORDER BY INA_DCORDEM ASC, INA_DTDATA_INSERT DESC
                        LIMIT 10";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_ARTIGOSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getArtigosInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM INA_INSTRUCOES_ADEQUACOES
                                ORDER BY INA_DCORDEM ASC
                                ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_ARTIGOSINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getCategoriaPrestadorInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM ADM_CAF_CATEGORIA_FORNECEDOR
                                ORDER BY CAF_DCNOME ASC
                                ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllPrestadorInfo() 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_FORNECEDOR
                                WHERE EXCLUIDO != '1' OR EXCLUIDO IS NULL
                                ORDER BY FOR_DCNOME ASC
                                ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllOSInfoByRange($DATA_INICIO, $DATA_FIM) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * 
                        FROM VIEW_OS 
                        WHERE MAE_DTPROGRAMADA BETWEEN :DATA_INICIO AND :DATA_FIM
                        ORDER BY MAE_DTPROGRAMADA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO);
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllVisitas($DATA_INICIO, $DATA_FIM) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                        FROM PDA_PUBLICIDADE_AUDIENCIA 
                        WHERE PDA_DTDATA BETWEEN :DATA_INICIO AND :DATA_FIM AND PDA_DCCATEGORIA = 'VISITA AREA PRESTADORES'
                        ORDER BY PDA_DTDATA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO);
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllVisitasInicial($DATA_INICIO, $DATA_FIM) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                        FROM PDA_PUBLICIDADE_AUDIENCIA 
                        WHERE PDA_DTDATA BETWEEN :DATA_INICIO AND :DATA_FIM AND PDA_DCCATEGORIA = 'VISITA AREA INICIAL'
                        ORDER BY PDA_DTDATA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO);
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllCliques($DATA_INICIO, $DATA_FIM) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * 
                        FROM PDA_PUBLICIDADE_AUDIENCIA 
                        WHERE PDA_DTDATA BETWEEN :DATA_INICIO AND :DATA_FIM AND PDA_DCCATEGORIA = 'CLICK'
                        ORDER BY PDA_DTDATA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO); 
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllCliquesById($DATA_INICIO, $DATA_FIM, $PDS_IDPRESTADOR_SERVICO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * 
                        FROM PDA_PUBLICIDADE_AUDIENCIA 
                        WHERE PDA_DTDATA BETWEEN :DATA_INICIO AND :DATA_FIM AND PDA_DCCATEGORIA = 'CLICK' AND PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO
                        ORDER BY PDA_DTDATA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO);
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->bindValue(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllOSInfoByRangeAndFunc($DATA_INICIO, $DATA_FIM, $USU_IDUSUARIO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * 
                        FROM VIEW_OS 
                        WHERE MAE_DTPROGRAMADA BETWEEN :DATA_INICIO AND :DATA_FIM AND USU_IDUSUARIO = :USU_IDUSUARIO
                        ORDER BY MAE_DTPROGRAMADA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO);
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllPropostasInfo($ORC_IDORCAMENTO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_PROPOSTAS
                                WHERE ORC_IDORCAMENTO = :ORC_IDORCAMENTO AND (EXCLUIDO != '1' OR EXCLUIDO IS NULL)
                                ORDER BY PRO_DTRESPOSTA ASC
                                ";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':ORC_IDORCAMENTO', $ORC_IDORCAMENTO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllAtividadesInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM ADM_MAN_MANUTENCAO_ATIVIDADE                               
                                ORDER BY MAN_DCTITULO ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllAtividadesAtivadasInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM ADM_MAN_MANUTENCAO_ATIVIDADE    
                                WHERE EXCLUIDO != '1' OR EXCLUIDO IS NULL                           
                                ORDER BY MAN_DCTITULO ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllFuncoesSistemasInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM ADM_FUS_FUNCOES_SISTEMAS ORDER BY FUS_DCNAME, CUS_DCFUNCAO ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllAtividadesPortariaInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM ADM_MAN_MANUTENCAO_ATIVIDADE
                                WHERE EXCLUIDO != '1' OR EXCLUIDO IS NULL AND MAN_STDISP_PORTARIA = 'SIM'
                                ORDER BY MAN_DCTITULO ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllChamadosInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT *
                                FROM SUP_SUPORTE
                                WHERE EXCLUIDO != '1' OR EXCLUIDO IS NULL
                                ORDER BY SUP_DTCADASTRO DESC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getAllResumoFinanceiroInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM RES_RESUMO_FINANCEIRO
                                ORDER BY RES_DTRESUMO DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPrestadorInfoById($FOR_IDFORNECEDOR)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM VW_FORNECEDOR
                                WHERE FOR_IDFORNECEDOR = :FOR_IDFORNECEDOR";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPropostaInfoById($ADM_IDPRO_PROPOSTAS)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM ADM_PRO_PROPOSTAS
                                WHERE ADM_IDPRO_PROPOSTAS = :ADM_IDPRO_PROPOSTAS";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ADM_IDPRO_PROPOSTAS', $ADM_IDPRO_PROPOSTAS, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }
        
        public function getAtividadeInfoById($MAN_IDMANUTENCAO_ATIVIDADE)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT *
                                FROM ADM_MAN_MANUTENCAO_ATIVIDADE
                                WHERE MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getListaInfo($LIS_DCAPARTAMENTO, $LIS_DCBLOCO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM LIS_LISTACONVIDADOS 
                WHERE 
                LIS_DCAPARTAMENTO = :LIS_DCAPARTAMENTO 
                AND LIS_DCBLOCO = :LIS_DCBLOCO
                ORDER BY LIS_DCNOME ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':LIS_DCAPARTAMENTO', $LIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DCBLOCO', $LIS_DCBLOCO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LISTAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getListaInfoByMorador($LIS_DCAPARTAMENTO, $LIS_DCBLOCO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM LIS_LISTACONVIDADOS 
                WHERE 
                LIS_DCAPARTAMENTO = :LIS_DCAPARTAMENTO 
                AND LIS_DCBLOCO = :LIS_DCBLOCO
                AND LIS_STSTATUS = 'ATIVO' 
                ORDER BY LIS_DCNOME ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':LIS_DCAPARTAMENTO', $LIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DCBLOCO', $LIS_DCBLOCO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LISTAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getConvidadoById($LIS_IDLISTACONVIDADOS)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM LIS_LISTACONVIDADOS WHERE LIS_IDLISTACONVIDADOS = :LIS_IDLISTACONVIDADOS";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':LIS_IDLISTACONVIDADOS', $LIS_IDLISTACONVIDADOS, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_CONVIDADOINFO = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getMoradorById($USU_DCAPARTAMENTO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM USU_USUARIO WHERE USU_DCAPARTAMENTO = :USU_DCAPARTAMENTO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_DCAPARTAMENTO', $USU_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_USERINFOBYID = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getMoradorFullInfoById($USU_IDUSUARIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT * FROM VW_USU_USUARIO_APARTAMENTO 
                WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND CON_IDCONDOMINIO = :CON_IDCONDOMINIO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                
                $stmt->execute();
                $this->ARRAY_USERINFOBYID = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getMembrosFullInfoById($USU_IDUSUARIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM MOR_MORADORES
                WHERE USU_IDUSUARIO = :USU_IDUSUARIO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                
                $stmt->execute();
                $this->ARRAY_MORADORESINFOBYID = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getMoradorByUserId($USU_IDUSUARIO, $CUS_DCBLOCO, $CUS_DCAPARTAMENTO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT * FROM VW_USU_USUARIO_APARTAMENTO 
                        WHERE 
                        USU_IDUSUARIO = :USU_IDUSUARIO 
                        AND CON_IDCONDOMINIO = :CON_IDCONDOMINIO
                        AND CUS_DCBLOCO = :CUS_DCBLOCO
                        AND CUS_DCAPARTAMENTO = :CUS_DCAPARTAMENTO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':CUS_DCBLOCO', $CUS_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':CUS_DCAPARTAMENTO', $CUS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_USERINFOBYID = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getUsuarioByCondo($CON_IDCONDOMINIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT * FROM VW_USU_USUARIO_APARTAMENTO 
                        WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        function getClientIP() {
            if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                // IP real quando o site está atrás do Cloudflare
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
        
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // Pode conter uma lista de IPs, pegamos o primeiro (IP real do cliente)
                $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($ipList[0]);
            }
        
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            }
        
            // IP direto sem proxy
            return $_SERVER['REMOTE_ADDR'];
        }

        public function insertLogInfo($LOG_DCTIPO, $LOG_DCUSUARIO, $LOG_DCNIVEL, $LOG_IDCONDOMINIO, $LOG_DCMSG)
        {       
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $LOG_DCIP = filter_var($this->getClientIP(), FILTER_VALIDATE_IP) ?: '0.0.0.0'; //sanitizado

            $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO LOG_LOGSISTEMA 
                        (LOG_DCTIPO, LOG_DCMSG, LOG_DCUSUARIO, LOG_DCNIVEL, LOG_DTLOG, LOG_IDCONDOMINIO, LOG_DCIP) 
                        VALUES (:LOG_DCTIPO, :LOG_DCMSG, :LOG_DCUSUARIO, :LOG_DCNIVEL, :LOG_DTLOG, :LOG_IDCONDOMINIO, :LOG_DCIP)";

                $stmt = $this->pdoSistema->prepare($sql);
            
                $stmt->bindParam(':LOG_DCTIPO', $LOG_DCTIPO, PDO::PARAM_STR);
                $stmt->bindParam(':LOG_DCMSG', $LOG_DCMSG, PDO::PARAM_STR);
                $stmt->bindParam(':LOG_DCUSUARIO', $LOG_DCUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':LOG_DTLOG', $DATA, PDO::PARAM_STR);
                $stmt->bindParam(':LOG_DCNIVEL', $LOG_DCNIVEL, PDO::PARAM_STR);   
                $stmt->bindParam(':LOG_IDCONDOMINIO', $LOG_IDCONDOMINIO, PDO::PARAM_STR);    
                $stmt->bindParam(':LOG_DCIP', $LOG_DCIP, PDO::PARAM_STR);              
                         
                $stmt->execute();
           
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }
        }

        public function insertNotificacaoFront($NOT_DCTITLE, $NOT_DCMSG, $NIVEL)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }
            $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            $NOT_DTINSERT = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO NOT_NOTIFICACOES  
                        (NOT_DCTITLE, NOT_DCMSG, NOT_DTINSERT) 
                        VALUES (:NOT_DCTITLE, :NOT_DCMSG, :NOT_DTINSERT)";

                $stmt = $this->pdo->prepare($sql);            
                $stmt->bindParam(':NOT_DCTITLE', $NOT_DCTITLE, PDO::PARAM_STR);
                $stmt->bindParam(':NOT_DCMSG', $NOT_DCMSG, PDO::PARAM_STR);
                $stmt->bindParam(':NOT_DTINSERT', $NOT_DTINSERT, PDO::PARAM_STR);
            
                $stmt->execute();

            // Obtém o ID da última inserção
            $NOT_IDNOTIFICACOES = $this->pdo->lastInsertId();

            $this->insertNotificacaoUsuarioFront($NOT_IDNOTIFICACOES, $NIVEL);
           
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function insertNotificacaoUsuarioFront($NOT_IDNOTIFICACOES, $NIVEL)  
        {       
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }
            if (!$this->pdo) {
                $this->conexao();
            }
       
            try {
                if($NIVEL == "TODOS") {

                    $sql = "SELECT USU_IDUSUARIO FROM CUS_CONDOUSUARIO WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO";
                    $stmt = $this->pdoSistema->prepare($sql); 
                    $stmt->bindValue(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_INT);  
                }
                else 
                    {
                        $sql = "SELECT USU_IDUSUARIO FROM CUS_CONDOUSUARIO WHERE CUS_DCFUNCAO = :CUS_DCFUNCAO";
                        $stmt = $this->pdoSistema->prepare($sql);   
                        $stmt->bindValue(':CUS_DCFUNCAO', $NIVEL, PDO::PARAM_INT);  
                    }

                $stmt->execute();
                $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN); 
        
                $sqlInsert = "INSERT INTO USN_NOTIFICACAO (USU_IDUSUARIO, NOT_IDNOTIFICACOES, USN_STLIDA, USN_STREMOVIDA) 
                              VALUES (:USU_IDUSUARIO, :NOT_IDNOTIFICACOES, 0, 0)";
                $stmtInsert = $this->pdo->prepare($sqlInsert);
        
                foreach ($usuarios as $USU_IDUSUARIO) {
                    $stmtInsert->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_INT);
                    $stmtInsert->bindValue(':NOT_IDNOTIFICACOES', $NOT_IDNOTIFICACOES, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
        
                return ["success" => true];
        
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }
        }


        public function insertNotificacaoFrontByUsuario($NOT_DCTITLE, $NOT_DCMSG, $USU_IDUSUARIO)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }
            $now = new DateTime(null, new DateTimeZone('America/Sao_Paulo'));
            $NOT_DTINSERT = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO NOT_NOTIFICACOES  
                        (NOT_DCTITLE, NOT_DCMSG, NOT_DTINSERT) 
                        VALUES (:NOT_DCTITLE, :NOT_DCMSG, :NOT_DTINSERT)";

                $stmt = $this->pdo->prepare($sql);            
                $stmt->bindParam(':NOT_DCTITLE', $NOT_DCTITLE, PDO::PARAM_STR);
                $stmt->bindParam(':NOT_DCMSG', $NOT_DCMSG, PDO::PARAM_STR);
                $stmt->bindParam(':NOT_DTINSERT', $NOT_DTINSERT, PDO::PARAM_STR);
            
                $stmt->execute();

            // Obtém o ID da última inserção
            $NOT_IDNOTIFICACOES = $this->pdo->lastInsertId();

            $this->insertNotificacaoUsuarioFrontByusuario($NOT_IDNOTIFICACOES, $USU_IDUSUARIO);
           
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function insertNotificacaoUsuarioFrontByusuario($NOT_IDNOTIFICACOES, $USU_IDUSUARIO) 
        {       
            if (!$this->pdo) {
                $this->conexao();
            }       
            try{
                $sqlInsert = "INSERT INTO USN_NOTIFICACAO (USU_IDUSUARIO, NOT_IDNOTIFICACOES, USN_STLIDA, USN_STREMOVIDA) 
                              VALUES (:USU_IDUSUARIO, :NOT_IDNOTIFICACOES, 0, 0)";
                $stmtInsert = $this->pdo->prepare($sqlInsert);       

                $stmtInsert->bindValue(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_INT);
                $stmtInsert->bindValue(':NOT_IDNOTIFICACOES', $NOT_IDNOTIFICACOES, PDO::PARAM_INT);
                $stmtInsert->execute();

        
                return ["success" => true];
        
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }
        }


        public function getNotificacaoByUsuarioFront($USU_IDUSUARIO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT USN.USU_IDUSUARIO, USN_IDNOTIFICACAO, NOT_DCTITLE, NOT_DCMSG, NOT_DTINSERT, USN.USN_STREMOVIDA FROM USN_NOTIFICACAO USN
                        INNER JOIN NOT_NOTIFICACOES NOF ON (NOF.NOT_IDNOTIFICACOES = USN.NOT_IDNOTIFICACOES)
                        WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND USN_STREMOVIDA = '0'
                        LIMIT 20";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_NOTIFICACAOFRONTINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }
        

        public function insertPetInfo($USU_IDUSUARIO, $PEM_DCNOME, $PEM_DCRACA, $PEM_DCTIPO, $PET_DCPATHFOTO, $PET_DCCOR)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "INSERT INTO PEM_PETMORADOR 
                        (USU_IDUSUARIO, PEM_DCNOME, PEM_DCRACA, PEM_DCTIPO, PET_DCPATHFOTO, PET_DCCOR) 
                        VALUES (:USU_IDUSUARIO, :PEM_DCNOME, :PEM_DCRACA, :PEM_DCTIPO, :PET_DCPATHFOTO, :PET_DCCOR)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':PEM_DCNOME', $PEM_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':PEM_DCRACA', $PEM_DCRACA, PDO::PARAM_STR);
                $stmt->bindParam(':PEM_DCTIPO', $PEM_DCTIPO, PDO::PARAM_STR);
                $stmt->bindParam(':PET_DCPATHFOTO', $PET_DCPATHFOTO, PDO::PARAM_STR);  
                $stmt->bindParam(':PET_DCCOR', $PET_DCCOR, PDO::PARAM_STR);
            
                $stmt->execute();
           
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function gravarMensagemSugestao($REC_DCMSG, $USU_IDUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO REC_RECLAMACAO 
                        (REC_DCMSG, REC_DTDATA, USU_IDUSUARIO) 
                        VALUES (:REC_DCMSG, :REC_DTDATA, :USU_IDUSUARIO)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':REC_DCMSG', $REC_DCMSG, PDO::PARAM_STR);
                $stmt->bindParam(':REC_DTDATA', $DATA, PDO::PARAM_STR);   
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);          
                $stmt->execute();
                $this->insertLogInfo("Insert", "-", USER_NIVEL, IDCONDOMINIO,"Enviada uma mensagem na caixa de sugestão: $REC_DCMSG");
           
                $response = array("success" => true, "message" => "A mensagem foi enviada com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                // Captura e retorna o erro
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Erro ao registrar a mensagem ou reclamação: $erro");  
                $response = array("success" => false, "message" => "A mensagem não foi enviada.");
                return json_encode($response); 
            }
        }

        public function insertPacoteInfo($ENC_DCOBSERVACAO, $ENC_DCBLOCO, $ENC_DCAPARTAMENTO, $ENC_DCFOTO="")
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');
            $ENC_STENCOMENDA = "INDISPONIVEL";

            $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';                    
            $codigo = substr(str_shuffle($caracteres), 0, 5);

            $ENC_DCHASHENTREGA = password_hash($codigo . $ENC_DCAPARTAMENTO . $ENC_DCBLOCO, PASSWORD_BCRYPT);

            try {
                $sql = "INSERT INTO ENC_ENCOMENDA 
                        (ENC_IDENCOMENDA, ENC_DCOBSERVACAO, ENC_DTENTREGA_PORTARIA, ENC_STENCOMENDA, ENC_DCHASHENTREGA, ENC_DCBLOCO, ENC_DCAPARTAMENTO, ENC_DCFOTO) 
                        VALUES (:ENC_IDENCOMENDA, :ENC_DCOBSERVACAO, :ENC_DTENTREGA_PORTARIA, :ENC_STENCOMENDA, :ENC_DCHASHENTREGA, :ENC_DCBLOCO, :ENC_DCAPARTAMENTO, :ENC_DCFOTO)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':ENC_DCOBSERVACAO', $ENC_DCOBSERVACAO, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_DTENTREGA_PORTARIA', $DATA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_STENCOMENDA', $ENC_STENCOMENDA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_IDENCOMENDA', $codigo, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_DCHASHENTREGA', $ENC_DCHASHENTREGA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_DCBLOCO', $ENC_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_DCAPARTAMENTO', $ENC_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_DCFOTO', $ENC_DCFOTO, PDO::PARAM_STR);                
            
                $stmt->execute();
                $this->insertLogInfo("insert", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Encomenda registrada para o Apto $ENC_DCAPARTAMENTO do Bloco $ENC_DCBLOCO com o código $codigo.");
                $response = array("success" => true, "message" => "Encomenda registrada para o Apto $ENC_DCAPARTAMENTO do Bloco $ENC_DCBLOCO com o código $codigo.");
                return json_encode($response); 
           
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Erro ao registrar a encomenda para o Apto $ENC_DCAPARTAMENTO do Bloco $ENC_DCBLOCO com o código $codigo. Erro: $erro");
                $response = array("success" => false, "message" => "Erro ao registrar a encomenda para o Apto $ENC_DCAPARTAMENTO do Bloco $ENC_DCBLOCO com o código $codigo");
                return json_encode($response); 
            }
        }

        public function getPopupImagePublish()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT PUB_DCIMG, PUB_DCLINK  FROM PUB_PUBLICIDADE
                        WHERE PUB_STSTATUS = 'ATIVA' AND PUB_DCTIPO LIKE '%IMAGEM%'";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_POPUPPUBLISHINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPendenciasInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM EPE_EVOLUCAO_PENDENCIA
                       ORDER BY EPE_DTLASTUPDATE DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_PENDENCIAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getVisitamtesCondoInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_VISITANTES
                       ORDER BY VIS_DCNOME DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_VISITANTESCONDO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getVisitamtesLogInfoByRange($DATA_INICIO, $DATA_FIM)
        {                
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VIL_VISITANTES_LOG
                        WHERE VIL_DTENTRADA BETWEEN :DATA_INICIO AND :DATA_FIM
                       ORDER BY VIL_DTENTRADA DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':DATA_INICIO', $DATA_INICIO);
                $stmt->bindValue(':DATA_FIM', $DATA_FIM);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getVisitamtesCondoInfoByIdVisitante($VIS_IDVISITANTE)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_VISITANTES
                       WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_VISITANTESCONDO = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        } 

        public function getVisitamtesCondoInfoByApBl($VIS_DCBLOCO, $VIS_DCAPARTAMENTO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_VISITANTES 
                        WHERE VIS_DCBLOCO = :VIS_DCBLOCO AND VIS_DCAPARTAMENTO = :VIS_DCAPARTAMENTO
                        ORDER BY VIS_DCNOME DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_DCBLOCO', $VIS_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCAPARTAMENTO', $VIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_VISITANTESCONDO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {

                $error = $e->getMessage();
                $LOG_DCTIPO = "Consulta"; 
                $LOG_DCMSG = "Erro ao buscar os parâmetros do sistema getVisitamtesCondoInfoByApBl: $error";                       
                $LOG_DCUSUARIO = USER_EMAIL;
                $LOG_DCNIVEL = "Error";
                $LOG_IDCONDOMINIO = IDCONDOMINIO;
                $this->insertLogInfo($LOG_DCTIPO, $LOG_DCMSG, $LOG_DCUSUARIO, $LOG_DCNIVEL, $LOG_IDCONDOMINIO);

                return ["error" => $e->getMessage()];
            }          
        }

        public function getVisitamtesCondoSalaoInfoByApBl($VIS_DCBLOCO, $VIS_DCAPARTAMENTO)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VW_VISITANTES 
                        WHERE VIS_DCBLOCO = :VIS_DCBLOCO AND VIS_DCAPARTAMENTO = :VIS_DCAPARTAMENTO AND VIS_STCONVIDADO_FESTA = 'SIM'
                        ORDER BY VIS_DCNOME DESC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_DCBLOCO', $VIS_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCAPARTAMENTO', $VIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_VISITANTESCONDO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getVisitamtesCondoInfoById($VIS_IDVISITANTE)
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM VIS_VISITANTE 
                        WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPendenciasInicialInfo()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM EPE_EVOLUCAO_PENDENCIA
                       ORDER BY EPE_DTLASTUPDATE DESC LIMIT 20";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_PENDENCIAINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getFooterPublish()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT PUB_DCDESC  FROM PUB_PUBLICIDADE
                        WHERE PUB_STSTATUS = 'ATIVA' AND PUB_DCTIPO LIKE '%TEXTO%'";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_FOOTERPUBLISHINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function checkPubliExisInfo($ID)
        {          
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {           
                $sql = "SELECT 1 FROM PUB_PUBLICIDADE WHERE MKT_IDMKTPUBLICIDADE = :MKT_IDMKTPUBLICIDADE";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MKT_IDMKTPUBLICIDADE', $ID, PDO::PARAM_STR);
                $stmt->execute();
        
                // Verifica se encontrou algum registro
                return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }
        }

        public function updatePubliInfo($PUB_DTINI, $PUB_DTFIM, $PUB_DCCLIENTEORIG, $PUB_STSTATUS, $MKT_IDMKTPUBLICIDADE, $PUB_DCIMG, $PUB_DCDESC, $PUB_DCTIPO, $PUB_DCLINK)
        {                            
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {

                $sql = "UPDATE PUB_PUBLICIDADE 
                        SET
                        PUB_DTINI = :PUB_DTINI,
                        PUB_DTFIM = :PUB_DTFIM,
                        PUB_DCCLIENTEORIG = :PUB_DCCLIENTEORIG,
                        PUB_STSTATUS = :PUB_STSTATUS,
                        PUB_DCIMG = :PUB_DCIMG,
                        PUB_DCDESC = :PUB_DCDESC,
                        PUB_DCTIPO = :PUB_DCTIPO,
                        PUB_DCLINK = :PUB_DCLINK
                        WHERE MKT_IDMKTPUBLICIDADE = :MKT_IDMKTPUBLICIDADE";                       

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PUB_DTINI', $PUB_DTINI, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DTFIM', $PUB_DTFIM, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCCLIENTEORIG', $PUB_DCCLIENTEORIG, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_STSTATUS', $PUB_STSTATUS, PDO::PARAM_STR);
                $stmt->bindParam(':MKT_IDMKTPUBLICIDADE', $MKT_IDMKTPUBLICIDADE, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCIMG', $PUB_DCIMG, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCDESC', $PUB_DCDESC, PDO::PARAM_STR); 
                $stmt->bindParam(':PUB_DCTIPO', $PUB_DCTIPO, PDO::PARAM_STR); 
                $stmt->bindParam(':PUB_DCLINK', $PUB_DCLINK, PDO::PARAM_STR);

            
                $stmt->execute();
           

                if($PUB_STSTATUS == "ATIVA")
                {
                    return "PUBLICADO";
                } 
                else
                    {
                        return "PENDENTE";
                    }
                    
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return "ERRO: Não foi possível atualizar a publicidade.";
            }
        }

        public function insertPubliInfo($PUB_DTINI, $PUB_DTFIM, $PUB_DCCLIENTEORIG, $PUB_STSTATUS, $MKT_IDMKTPUBLICIDADE, $PUB_DCIMG, $PUB_DCDESC, $PUB_DCTIPO, $PUB_DCLINK)
        {                            
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {

                $sql = "INSERT INTO PUB_PUBLICIDADE 
                        (PUB_DTINI, PUB_DTFIM, PUB_DCCLIENTEORIG, PUB_STSTATUS, MKT_IDMKTPUBLICIDADE, PUB_DCIMG, PUB_DCDESC, PUB_DCTIPO, PUB_DCLINK) 
                        VALUES (:PUB_DTINI, :PUB_DTFIM, :PUB_DCCLIENTEORIG, :PUB_STSTATUS, :MKT_IDMKTPUBLICIDADE, :PUB_DCIMG, :PUB_DCDESC, :PUB_DCTIPO, :PUB_DCLINK)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PUB_DTINI', $PUB_DTINI, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DTFIM', $PUB_DTFIM, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCCLIENTEORIG', $PUB_DCCLIENTEORIG, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_STSTATUS', $PUB_STSTATUS, PDO::PARAM_STR);
                $stmt->bindParam(':MKT_IDMKTPUBLICIDADE', $MKT_IDMKTPUBLICIDADE, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCIMG', $PUB_DCIMG, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCDESC', $PUB_DCDESC, PDO::PARAM_STR);
                $stmt->bindParam(':PUB_DCTIPO', $PUB_DCTIPO, PDO::PARAM_STR); 
                $stmt->bindParam(':PUB_DCLINK', $PUB_DCLINK, PDO::PARAM_STR);

            
                $stmt->execute();

                if($PUB_STSTATUS == "ATIVA")
                {
                    return "PUBLICADO";
                } 
                else
                    {
                        return "PENDENTE";
                    }

                        

            } catch (PDOException $e) {
                // Captura e retorna o erro
                return "ERRO: Não foi possível inserir a publicidade.";
            }
        }

        public function insertConvidadoListaInfo($LIS_DCNOME, $USU_IDUSUARIO, $LIS_DCDOCUMENTO, $LIS_STSTATUS, $LIS_DCAPARTAMENTO, $LIS_DCBLOCO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO LIS_LISTACONVIDADOS 
                        (LIS_DCNOME, USU_IDUSUARIO, LIS_DCDOCUMENTO, LIS_DTCADASTRO, LIS_STSTATUS, LIS_DCAPARTAMENTO, LIS_DCBLOCO) 
                        VALUES (:LIS_DCNOME, :USU_IDUSUARIO, :LIS_DCDOCUMENTO, :LIS_DTCADASTRO, :LIS_STSTATUS, :LIS_DCAPARTAMENTO, :LIS_DCBLOCO)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':LIS_DCNOME', $LIS_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DCDOCUMENTO', $LIS_DCDOCUMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DTCADASTRO', $DATA, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_STSTATUS', $LIS_STSTATUS, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DCAPARTAMENTO', $LIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DCBLOCO', $LIS_DCBLOCO, PDO::PARAM_STR);
                
            
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => " Convidado cadastrado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function sendSolicitacaoPropostaInfo($ORC_IDORCAMENTO, $FOR_IDFORNECEDOR)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $PRO_DTSOLICITACAO = $now->format('Y-m-d H:i:s');
            $PRO_DCSTATUS = "AGUARDANDO";

            try {
                $this->pdo->beginTransaction(); 

                $sql = "INSERT INTO ADM_PRO_PROPOSTAS 
                        (ORC_IDORCAMENTO, FOR_IDFORNECEDOR, PRO_DCSTATUS, PRO_DTSOLICITACAO) 
                        VALUES (:ORC_IDORCAMENTO, :FOR_IDFORNECEDOR, :PRO_DCSTATUS, :PRO_DTSOLICITACAO)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':ORC_IDORCAMENTO', $ORC_IDORCAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DCSTATUS', $PRO_DCSTATUS, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DTSOLICITACAO', $PRO_DTSOLICITACAO, PDO::PARAM_STR);

                $stmt->execute();

                $ADM_IDPRO_PROPOSTAS = $this->pdo->lastInsertId();

                $secretKey = getenv('ENV_SECRET_KEY');
                $payload = [
                    "iat" => time(),  
                    "exp" => time() + 31536000,
                    "data" => [
                        "bdid" => $this->BANCODEDADOS_CONDOMINIO,
                        "idProposta" => $ADM_IDPRO_PROPOSTAS,
                        "idOrcamento" => $ORC_IDORCAMENTO
                    ] 
                ];
        
                $PRO_DCTOKEN = JWT::encode($payload, $secretKey, 'HS256');  

                $sqlUpdate = "UPDATE ADM_PRO_PROPOSTAS SET PRO_DCTOKEN = :PRO_DCTOKEN WHERE ADM_IDPRO_PROPOSTAS = :ADM_IDPRO_PROPOSTAS";
                $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':PRO_DCTOKEN', $PRO_DCTOKEN, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':ADM_IDPRO_PROPOSTAS', $ADM_IDPRO_PROPOSTAS, PDO::PARAM_INT);
                $stmtUpdate->execute();                    
                $this->pdo->commit(); 

                $this->insertLogInfo("Info", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"gerado token para solicitação de orçamento : $PRO_DCTOKEN");
            
                return $PRO_DCTOKEN;
            } catch (PDOException $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                $erro = $e->getMessage();
                $this->insertLogInfo("Info", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"erro ao gerar o token para solicitação de orçamento: $erro");
                return "Erro ao gerar o token";
            }
        }

        
        public function insertSolicitacaoPropostaSindicoInfo($ORC_IDORCAMENTO, $FOR_IDFORNECEDOR, $PRO_NMRESPONSAVEL, $PRO_NMVALOR, $PRO_DTVALIDADE_PROPOSTA, $PRO_DCDESC, $PRO_DCFOTO, $PRO_DCARQUIVO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $PRO_DTSOLICITACAO = $now->format('Y-m-d H:i:s');
            $PRO_DCSTATUS = "EM ANALISE";

            try {
                $this->pdo->beginTransaction(); 

                $sql = "INSERT INTO ADM_PRO_PROPOSTAS 
                        (PRO_DTSOLICITACAO, ORC_IDORCAMENTO, FOR_IDFORNECEDOR, PRO_NMRESPONSAVEL, PRO_NMVALOR, PRO_DTVALIDADE_PROPOSTA, PRO_DCDESC, PRO_DCFOTO, PRO_DCARQUIVO, PRO_DCSTATUS) 
                        VALUES (:PRO_DTSOLICITACAO, :ORC_IDORCAMENTO, :FOR_IDFORNECEDOR, :PRO_NMRESPONSAVEL, :PRO_NMVALOR, :PRO_DTVALIDADE_PROPOSTA, :PRO_DCDESC, :PRO_DCFOTO, :PRO_DCARQUIVO, :PRO_DCSTATUS)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':ORC_IDORCAMENTO', $ORC_IDORCAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_NMRESPONSAVEL', $PRO_NMRESPONSAVEL, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DTSOLICITACAO', $PRO_DTSOLICITACAO, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_NMVALOR', $PRO_NMVALOR, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DTVALIDADE_PROPOSTA', $PRO_DTVALIDADE_PROPOSTA, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DCDESC', $PRO_DCDESC, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DCFOTO', $PRO_DCFOTO, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DCARQUIVO', $PRO_DCARQUIVO, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DCSTATUS', $PRO_DCSTATUS, PDO::PARAM_STR);

                $stmt->execute();
                $this->pdo->commit();

                $this->insertLogInfo("insert", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Nova proposta comercial cadastrada pelo Síndico.");
                $response = array("success" => true, "message" => "Nova proposta comercial cadastrada pelo Síndico.");                
                return json_encode($response); 

            } catch (PDOException $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                $erro = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao cadastrar uma nova proposta comercial pelo Síndico.: $erro");
                $response = array("success" => false, "message" => "Houve um erro ao cadastrar uma nova proposta comercial pelo Síndico.: $erro");                
                return json_encode($response); 
            }
        }

        public function insertVisitanteInfo($VIS_STPRESTADOR_SERVICO, $VIS_DCOBS, $VIS_DCDOCUMENTO, $VIS_DCNOME, $VIS_DCTELEFONE, $VIS_DCBLOCO, $VIS_DCAPARTAMENTO, $VIS_DCPLACA_VEICULO, $VIS_DCCADASTRO_RESPONSAVEL, $VIS_DCFOTO_VISITANTE=NULL)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $this->pdo->beginTransaction(); 

                $sql = "INSERT INTO VIS_VISITANTE 
                        (VIS_STPRESTADOR_SERVICO, VIS_DCOBS, VIS_DCDOCUMENTO, VIS_DCNOME, VIS_DCTELEFONE, VIS_DCBLOCO, VIS_DCAPARTAMENTO, VIS_DCFOTO_VISITANTE, VIS_DTCADASTRO, VIS_DCPLACA_VEICULO, VIS_DCCADASTRO_RESPONSAVEL) 
                        VALUES (:VIS_STPRESTADOR_SERVICO, :VIS_DCOBS, :VIS_DCDOCUMENTO, :VIS_DCNOME, :VIS_DCTELEFONE, :VIS_DCBLOCO, :VIS_DCAPARTAMENTO, :VIS_DCFOTO_VISITANTE, :VIS_DTCADASTRO, :VIS_DCPLACA_VEICULO, :VIS_DCCADASTRO_RESPONSAVEL)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':VIS_STPRESTADOR_SERVICO', $VIS_STPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCOBS', $VIS_DCOBS, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCDOCUMENTO', $VIS_DCDOCUMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCNOME', $VIS_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCTELEFONE', $VIS_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCBLOCO', $VIS_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCAPARTAMENTO', $VIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCFOTO_VISITANTE', $VIS_DCFOTO_VISITANTE, PDO::PARAM_STR);    
                $stmt->bindParam(':VIS_DTCADASTRO', $DATA, PDO::PARAM_STR);    
                $stmt->bindParam(':VIS_DCPLACA_VEICULO', $VIS_DCPLACA_VEICULO, PDO::PARAM_STR);   
                $stmt->bindParam(':VIS_DCCADASTRO_RESPONSAVEL', $VIS_DCCADASTRO_RESPONSAVEL, PDO::PARAM_STR);
                $stmt->execute();

                $visitanteId = $this->pdo->lastInsertId();

                $secretKey = getenv('ENV_SECRET_KEY');
                $payload = [
                    "iat" => time(),  
                    "exp" => time() + 31536000, //1 ano de exp
                    "data" => [
                        "bdid" => $this->BANCODEDADOS_CONDOMINIO,
                        "id" => $visitanteId
                    ] 
                ];
        
                $VIS_DCJWT_CONVITE = JWT::encode($payload, $secretKey, 'HS256');  

                // Atualiza a coluna VIS_DCJWT_CONVITE
                $sqlUpdate = "UPDATE VIS_VISITANTE SET VIS_DCJWT_CONVITE = :VIS_DCJWT_CONVITE WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";
                $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':VIS_DCJWT_CONVITE', $VIS_DCJWT_CONVITE, PDO::PARAM_STR);
                $stmtUpdate->bindParam(':VIS_IDVISITANTE', $visitanteId, PDO::PARAM_INT);
                $stmtUpdate->execute();                    
                $this->pdo->commit(); 
            
                return "Visitante cadastrado com sucesso.";
            } catch (PDOException $e) {
                $error = $e->getMessage(); 
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Cadastro do visitante $VIS_DCNOME falhou. $error");
                return "Erro ao cadastrar visitante $e.";
            }
        }

        public function updateVisitanteInfo($VIS_IDVISITANTE, $VIS_STPRESTADOR_SERVICO, $VIS_DCOBS, $VIS_DCDOCUMENTO, $VIS_DCNOME, $VIS_DCTELEFONE, $VIS_DCBLOCO, $VIS_DCAPARTAMENTO, $VIS_DCPLACA_VEICULO, $VIS_DCFOTO_VISITANTE)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }


            try {
                $this->pdo->beginTransaction(); 

                $sql = "UPDATE VIS_VISITANTE SET 
                            VIS_DCDOCUMENTO = :VIS_DCDOCUMENTO,
                            VIS_STPRESTADOR_SERVICO = :VIS_STPRESTADOR_SERVICO,
                            VIS_DCOBS = :VIS_DCOBS,
                            VIS_DCNOME = :VIS_DCNOME,
                            VIS_DCTELEFONE = :VIS_DCTELEFONE,
                            VIS_DCBLOCO = :VIS_DCBLOCO,
                            VIS_DCAPARTAMENTO = :VIS_DCAPARTAMENTO,"; 

                if (!empty($VIS_DCFOTO_VISITANTE)) {
                    $sql .= " VIS_DCFOTO_VISITANTE = :VIS_DCFOTO_VISITANTE,";
                }

                $sql .= " VIS_DCPLACA_VEICULO = :VIS_DCPLACA_VEICULO
                          WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_INT);
                $stmt->bindParam(':VIS_DCDOCUMENTO', $VIS_DCDOCUMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCNOME', $VIS_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCTELEFONE', $VIS_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCBLOCO', $VIS_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCAPARTAMENTO', $VIS_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_STPRESTADOR_SERVICO', $VIS_STPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_DCOBS', $VIS_DCOBS, PDO::PARAM_STR);

                if (!empty($VIS_DCFOTO_VISITANTE)) {
                    $stmt->bindParam(':VIS_DCFOTO_VISITANTE', $VIS_DCFOTO_VISITANTE, PDO::PARAM_STR);    
                }

                $stmt->bindParam(':VIS_DCPLACA_VEICULO', $VIS_DCPLACA_VEICULO, PDO::PARAM_STR);  

                $stmt->execute();

                $this->pdo->commit(); 
                return "Visitante atualizado com sucesso.";

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                return "Erro ao atualizar visitante: " . $e->getMessage();
            }
        }      

        public function updateVisitanteChegadaInfo($VIS_IDVISITANTE, $VIS_DCNOME)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $VIS_DTENTRADA = $now->format('Y-m-d H:i:s');

            try {
                $this->pdo->beginTransaction(); 

                $sql = "UPDATE VIS_VISITANTE SET 
                            VIS_DTENTRADA = :VIS_DTENTRADA
                            WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE"; 

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_INT);
                $stmt->bindParam(':VIS_DTENTRADA', $VIS_DTENTRADA, PDO::PARAM_STR);
                $stmt->execute();

                $this->pdo->commit(); 

                $response = array("success" => true, "message" => "Entrada do visitante $VIS_DCNOME registrada com sucesso.");
                $this->insertLogInfo("info", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Entrada do visitante $VIS_DCNOME registrada com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage(); 
                $response = array("success" => false, "message" => "Houve um erro ao atualizar a entrada do visitante.");
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao atualizar a entrada do visitante: $erro");
                return json_encode($response); 
            }
        }    

        public function insertPublicidadeInfo($PUC_IDPUBLICIDADE_CATEGORIA, $PDS_DCNOME_PRESTADOR, $USU_IDUSUARIO, $PDS_DCCAMPANHA, $PDS_DTPUB_INI, $PDS_DTPUB_FIM, $PDS_DCORDEM, $PDS_DCURL, $PDS_DCHEXCOLORBG, $PDS_DCOBS, $PDS_DCENDERECO, $PDS_DCIMGFILENAME)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            $now = new DateTime($PDS_DTPUB_INI); // Cria um objeto DateTime com o valor recebido
            $PDS_DTPUB_INI = $now->format('Y-m-d H:i:s'); // Formata como 'Y-m-d H:i:s'

            $now = new DateTime($PDS_DTPUB_FIM); // Cria um objeto DateTime com o valor recebido
            $PDS_DTPUB_FIM = $now->format('Y-m-d H:i:s'); // Formata como 'Y-m-d H:i:s'

            try {
                $sql = "INSERT INTO PDS_PUBLICIDADE 
                        (PUC_IDPUBLICIDADE_CATEGORIA, PDS_DCNOME_PRESTADOR, USU_IDUSUARIO, PDS_DCCAMPANHA, PDS_DTPUB_INI, PDS_DTPUB_FIM, PDS_DCORDEM, PDS_DCURL, PDS_DCHEXCOLORBG, PDS_DCOBS, PDS_DCIMGFILENAME, PDS_DCENDERECO, PDS_DTCADASTRO) 
                        VALUES (:PUC_IDPUBLICIDADE_CATEGORIA, :PDS_DCNOME_PRESTADOR, :USU_IDUSUARIO, :PDS_DCCAMPANHA, :PDS_DTPUB_INI, :PDS_DTPUB_FIM, :PDS_DCORDEM, :PDS_DCURL, :PDS_DCHEXCOLORBG, :PDS_DCOBS, :PDS_DCIMGFILENAME, :PDS_DCENDERECO, :PDS_DTCADASTRO)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PUC_IDPUBLICIDADE_CATEGORIA', $PUC_IDPUBLICIDADE_CATEGORIA, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCNOME_PRESTADOR', $PDS_DCNOME_PRESTADOR, PDO::PARAM_STR);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCCAMPANHA', $PDS_DCCAMPANHA, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DTPUB_INI', $PDS_DTPUB_INI, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DTPUB_FIM', $PDS_DTPUB_FIM, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCORDEM', $PDS_DCORDEM, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCURL', $PDS_DCURL, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCHEXCOLORBG', $PDS_DCHEXCOLORBG, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCOBS', $PDS_DCOBS, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCIMGFILENAME', $PDS_DCIMGFILENAME, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DTCADASTRO', $DATA, PDO::PARAM_STR);        
                $stmt->bindParam(':PDS_DCENDERECO', $PDS_DCENDERECO, PDO::PARAM_STR);         
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Publicidade cadastrada com sucesso");
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage(); 
                $response = array("success" => false, "message" => "Houve um erro ao inserir a publicidade. - $erro");
                return json_encode($response); 
            }
        }

        public function insertEntradaVisitanteLog(
            $VIL_STPRESTADOR_SERVICO,
            $VIL_DCOBS,
            $VIL_DCNOME,
            $VIL_DCDOC,
            $VIL_DCTELEFONE,
            $VIL_DCPLACA_VEICULO,
            $VIL_DCVAGA_GARAGEM,
            $VIL_DCBLOCO,
            $VIL_DCAPARTAMENTO,
            $VIL_DCFOTO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $VIL_DTENTRADA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO VIL_VISITANTES_LOG 
                        (VIL_STPRESTADOR_SERVICO, VIL_DCOBS, VIL_DCNOME, VIL_DCDOC, VIL_DCFOTO, VIL_DCTELEFONE, VIL_DCPLACA_VEICULO, VIL_DCVAGA_GARAGEM, VIL_DCBLOCO, VIL_DCAPARTAMENTO, VIL_DTENTRADA) 
                        VALUES (:VIL_STPRESTADOR_SERVICO, :VIL_DCOBS, :VIL_DCNOME, :VIL_DCDOC, :VIL_DCFOTO, :VIL_DCTELEFONE, :VIL_DCPLACA_VEICULO, :VIL_DCVAGA_GARAGEM, :VIL_DCBLOCO, :VIL_DCAPARTAMENTO, :VIL_DTENTRADA)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':VIL_DCNOME', $VIL_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCDOC', $VIL_DCDOC, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCFOTO', $VIL_DCFOTO, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCTELEFONE', $VIL_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCPLACA_VEICULO', $VIL_DCPLACA_VEICULO, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCVAGA_GARAGEM', $VIL_DCVAGA_GARAGEM, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCBLOCO', $VIL_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DCAPARTAMENTO', $VIL_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':VIL_DTENTRADA', $VIL_DTENTRADA, PDO::PARAM_STR);  
                $stmt->bindParam(':VIL_STPRESTADOR_SERVICO', $VIL_STPRESTADOR_SERVICO, PDO::PARAM_STR); 
                $stmt->bindParam(':VIL_DCOBS', $VIL_DCOBS, PDO::PARAM_STR);     
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Visitante registrado com sucesso");
                $this->insertLogInfo("info", "SISTEMA", "-", IDCONDOMINIO,"O Visitante $VIL_DCNOME foi registrado com sucesso no histórico."); 
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage(); 
                $this->insertLogInfo("info", "SISTEMA", "-", IDCONDOMINIO,"Houve um erro ao registrar o visitante. - $erro"); 
                $response = array("success" => false, "message" => "Houve um erro ao registrar o visitante $VIL_DCNOME no histórico. - $erro");
                return json_encode($response); 
            }
        }

        public function updatePublicidadeInfo($PDS_IDPRESTADOR_SERVICO, $PUC_IDPUBLICIDADE_CATEGORIA, $PDS_DCNOME_PRESTADOR, $USU_IDUSUARIO, $PDS_DCCAMPANHA, $PDS_DTPUB_INI, $PDS_DTPUB_FIM, $PDS_DCORDEM, $PDS_DCURL, $PDS_DCHEXCOLORBG, $PDS_DCENDERECO, $PDS_DCOBS, $PDS_DCIMGFILENAME)
        {       
                if (!$this->pdo) {
                $this->conexao();
            }
        
            $PDS_DTPUB_INI = (new DateTime($PDS_DTPUB_INI))->format('Y-m-d H:i:s');
            $PDS_DTPUB_FIM = (new DateTime($PDS_DTPUB_FIM))->format('Y-m-d H:i:s');
        
            try {
                // Monta os campos obrigatórios
                $sql = "UPDATE PDS_PUBLICIDADE SET 
                            PUC_IDPUBLICIDADE_CATEGORIA = :PUC_IDPUBLICIDADE_CATEGORIA,
                            PDS_DCNOME_PRESTADOR = :PDS_DCNOME_PRESTADOR,
                            USU_IDUSUARIO = :USU_IDUSUARIO,
                            PDS_DCCAMPANHA = :PDS_DCCAMPANHA,
                            PDS_DTPUB_INI = :PDS_DTPUB_INI,
                            PDS_DTPUB_FIM = :PDS_DTPUB_FIM,
                            PDS_DCORDEM = :PDS_DCORDEM,
                            PDS_DCURL = :PDS_DCURL,
                            PDS_DCHEXCOLORBG = :PDS_DCHEXCOLORBG,
                            PDS_DCENDERECO = :PDS_DCENDERECO,
                            PDS_DCOBS = :PDS_DCOBS";

                // Se houver imagem, inclui na query
                if (!empty($PDS_DCIMGFILENAME)) {
                    $sql .= ", PDS_DCIMGFILENAME = :PDS_DCIMGFILENAME";
                }
            
                $sql .= " WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO";
            
                $stmt = $this->pdo->prepare($sql); 
            
                // Parâmetros obrigatórios
                $stmt->bindParam(':PUC_IDPUBLICIDADE_CATEGORIA', $PUC_IDPUBLICIDADE_CATEGORIA, PDO::PARAM_STR); 
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCNOME_PRESTADOR', $PDS_DCNOME_PRESTADOR, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCCAMPANHA', $PDS_DCCAMPANHA, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DTPUB_INI', $PDS_DTPUB_INI, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DTPUB_FIM', $PDS_DTPUB_FIM, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCORDEM', $PDS_DCORDEM, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCURL', $PDS_DCURL, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCHEXCOLORBG', $PDS_DCHEXCOLORBG, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_DCOBS', $PDS_DCOBS, PDO::PARAM_STR); 
                $stmt->bindParam(':PDS_DCENDERECO', $PDS_DCENDERECO, PDO::PARAM_STR);
            
                // Somente se houver imagem
                if (!empty($PDS_DCIMGFILENAME)) {
                    $stmt->bindParam(':PDS_DCIMGFILENAME', $PDS_DCIMGFILENAME, PDO::PARAM_STR);
                }
            
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
            
                $stmt->execute();
            
                return json_encode(["success" => true, "message" => "Publicidade atualizada com sucesso"]);
            
            } catch (PDOException $e) {
                return json_encode([
                    "success" => false,
                    "message" => "Erro ao atualizar publicidade. - " . $e->getMessage()
                ]);
            }
        }

        public function insertPublicidadeAudiencia($PDS_IDPRESTADOR_SERVICO, $PDA_DCCATEGORIA, $PDA_NMVALOR, $USU_IDUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $PDA_DTDATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO PDA_PUBLICIDADE_AUDIENCIA 
                        (PDS_IDPRESTADOR_SERVICO, PDA_DCCATEGORIA, PDA_DTDATA, PDA_NMVALOR, USU_IDUSUARIO) 
                        VALUES (:PDS_IDPRESTADOR_SERVICO, :PDA_DCCATEGORIA, :PDA_DTDATA, :PDA_NMVALOR, :USU_IDUSUARIO)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':PDA_DCCATEGORIA', $PDA_DCCATEGORIA, PDO::PARAM_STR);
                $stmt->bindParam(':PDA_DTDATA', $PDA_DTDATA, PDO::PARAM_STR);   
                $stmt->bindParam(':PDA_NMVALOR', $PDA_NMVALOR, PDO::PARAM_STR);  
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);   
                $stmt->execute();            

            } catch (PDOException $e) {
                $erro = $e->getMessage(); 
                $response = array("success" => false, "message" => "$erro");
                echo json_encode($response); 
            }
        }

        //funções de manutenção --------------------------------------------------------------//

        public function getCondominiosInstalados()
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT CON_IDCONDOMINIO, CON_DCCONDOMINIO FROM CON_CONDOMINIO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function deleteOldLogs()
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try {
                $sql = "DELETE FROM LOG_LOGSISTEMA WHERE LOG_DTLOG < NOW() - INTERVAL 6 MONTH";
                $stmt = $this->pdoSistema->prepare($sql);            
                $stmt->execute();  

                $linhasDeletadas = $stmt->rowCount(); 
                return "Foram deletadas $linhasDeletadas linhas da tabela de logs";

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                return "Erro ao efetuar a manutenção da tabela LOG_LOGSISTEMA: $erro";
                return ["error" => $e->getMessage()];
            }      
        }

        public function deleteOldNot()
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
                $sql = "DELETE FROM NOT_NOTIFICACOES WHERE NOT_DTINSERT < NOW() - INTERVAL 6 MONTH";
                $stmt = $this->pdo->prepare($sql);            
                $stmt->execute();  

                $linhasDeletadas = $stmt->rowCount(); 
                return "Foram deletadas $linhasDeletadas linhas da tabela de Notificações";      
        }

        public function deleteOldPropostasOrcNaoRespondida()
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
                $sql = "DELETE FROM ADM_PRO_PROPOSTAS WHERE PRO_DCSTATUS = 'AGUARDANDO' AND PRO_DTSOLICITACAO < NOW() - INTERVAL 6 MONTH";
                $stmt = $this->pdo->prepare($sql);            
                $stmt->execute();  

                $linhasDeletadas = $stmt->rowCount(); 
                return "Foram deletadas $linhasDeletadas linhas da tabela de Propostas Comerciais";      
        }

        public function deleteOldRecl()
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
                $sql = "DELETE FROM REC_RECLAMACAO WHERE REC_DTDATA < NOW() - INTERVAL 6 MONTH";
                $stmt = $this->pdo->prepare($sql);            
                $stmt->execute();        

                $linhasDeletadas = $stmt->rowCount(); 
                return "Foram deletadas $linhasDeletadas linhas da tabela de Mensagens";  
        }

        //funções de manutenção --------------------------------------------------------------//

        public function deleteArtigoInfo($INA_IDINSTRUCOES_ADEQUACOES)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM INA_INSTRUCOES_ADEQUACOES WHERE INA_IDINSTRUCOES_ADEQUACOES = :INA_IDINSTRUCOES_ADEQUACOES";

                $stmt = $this->pdo->prepare($sql); 
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':INA_IDINSTRUCOES_ADEQUACOES', $INA_IDINSTRUCOES_ADEQUACOES, PDO::PARAM_STR);
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Artigo deletado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function deleteUsuarioById($CUS_IDCONDOUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try {
                $sql = "DELETE FROM CUS_CONDOUSUARIO WHERE CUS_IDCONDOUSUARIO = :CUS_IDCONDOUSUARIO AND CUS_DCFUNCAO != 'SUPORTE'";
                $stmt = $this->pdoSistema->prepare($sql);
            
                $stmt->bindParam(':CUS_IDCONDOUSUARIO', $CUS_IDCONDOUSUARIO, PDO::PARAM_STR);
                $stmt->execute(); 

                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O usuário com id $CUS_IDCONDOUSUARIO foi excluído com sucesso.");  

                return "Usuário excluído com sucesso.";
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A exclusão do usuário $CUS_IDCONDOUSUARIO falhou: $erro");  
                return $e->getMessage();
            }
        }

        public function deleteMoradorById($USU_IDUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexaoSistema();
            }

            try {
                $sql = "DELETE FROM MOR_MORADORES WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute(); 

                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Os moradores do usuário com id  $USU_IDUSUARIO foram excluídos com sucesso.");  

                return "Moradores excluídos com sucesso.";
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A exclusão dos moradores do usuário com id  $USU_IDUSUARIO falhou.: $erro");  
                return $e->getMessage();
            }
        }

        public function deleteModeloOrcamentoById($ORC_IDORCAMENTO)
        {
        if (!$this->pdo) {
            $this->conexao(); 
        }
        
        $now = new DateTime(); 
        $DATA = $now->format('Y-m-d H:i:s');

        try {
            // Atualiza o orçamento
            $sql = "UPDATE ADM_ORC_ORCAMENTO SET EXCLUIDO = '1', ORC_DTUPDATE = :ORC_DTUPDATE WHERE ORC_IDORCAMENTO = :ORC_IDORCAMENTO";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':ORC_IDORCAMENTO', $ORC_IDORCAMENTO, PDO::PARAM_STR);
            $stmt->bindParam(':ORC_DTUPDATE', $DATA, PDO::PARAM_STR);
            $stmt->execute();

            // Busca os IDs relacionados na tabela ADM_PRO_PROPOSTAS
            $sql = "SELECT ADM_IDPRO_PROPOSTAS FROM ADM_PRO_PROPOSTAS WHERE ORC_IDORCAMENTO = :ORC_IDORCAMENTO";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':ORC_IDORCAMENTO', $ORC_IDORCAMENTO, PDO::PARAM_STR);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);


            if ($resultados) {
                $ids = array_column($resultados, 'ADM_IDPRO_PROPOSTAS');
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                // Monta os valores: primeiro PRO_DTUPDATE, depois os IDs
                $sql = "UPDATE ADM_PRO_PROPOSTAS SET EXCLUIDO = '1', PRO_DTUPDATE = ? WHERE ADM_IDPRO_PROPOSTAS IN ($placeholders)";
                $stmt = $this->pdo->prepare($sql);

                // Primeiro valor é o DATA
                $stmt->bindValue(1, $DATA, PDO::PARAM_STR);

                // Depois os IDs, começando do índice 2
                foreach ($ids as $index => $id) {
                    $stmt->bindValue($index + 2, $id, PDO::PARAM_STR);
                }

                $stmt->execute();
            }

            $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O modelo de orçamento com id $ORC_IDORCAMENTO foi excluído com sucesso.");


            $response = array("success" => true, "message" => "Modelo de proposta excluído com sucesso.");
            return json_encode($response); 

        } catch (PDOException $e) {
            $erro = $e->getMessage();
            $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A exclusão do modelo de orçamento $ORC_IDORCAMENTO falhou: $erro");
            $response = array("success" => false, "message" => "A exclusão do modelo de orçamento falhou.");
            return json_encode($response); 
        }
        }


        public function deleteFuncionarioById($USU_IDUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try {
                $sql = "DELETE FROM CUS_CONDOUSUARIO WHERE USU_IDUSUARIO = :USU_IDUSUARIO AND CUS_DCFUNCAO != 'SUPORTE'";
                $stmt = $this->pdoSistema->prepare($sql);            
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();

                return "Usuário excluído com sucesso.";
            } catch (PDOException $e) { 
                return $e->getMessage();
            }
        }

        public function deleteGuiaRapidoById($GUR_IDGUIA_RAPIDO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try {
                $sql = "DELETE FROM GUR_GUIA_RAPIDO WHERE GUR_IDGUIA_RAPIDO = :GUR_IDGUIA_RAPIDO";
                $stmt = $this->pdoSistema->prepare($sql);            
                $stmt->bindParam(':GUR_IDGUIA_RAPIDO', $GUR_IDGUIA_RAPIDO, PDO::PARAM_STR);
                $stmt->execute();

                return "1";
            } catch (PDOException $e) { 
                return $e->getMessage();
            }
        }

        public function deleteTaskById($MAN_IDMANUTENCAO_ATIVIDADE)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "UPDATE ADM_MAN_MANUTENCAO_ATIVIDADE SET EXCLUIDO = '1' WHERE MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE";
                $stmt = $this->pdo->prepare($sql);            
                $stmt->bindParam(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->execute();

                // Busca os IDs relacionados na tabela ADM_PRO_PROPOSTAS
                $sql = "SELECT ORC_IDORCAMENTO FROM ADM_ORC_ORCAMENTO WHERE MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Itera sobre os resultados e chama a função
                if ($resultados) {
                    foreach ($resultados as $row) {
                        $orc_id = $row['ORC_IDORCAMENTO'];
                    
                        // Chama sua função aqui, passando o ID
                        $this->deleteModeloOrcamentoById($orc_id);
                    }
                }

                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A atividade com id $MAN_IDMANUTENCAO_ATIVIDADE foi excluída com sucesso.");
                $response = array("success" => true, "message" => "Atividade excluída com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao excluir a atividade com id $MAN_IDMANUTENCAO_ATIVIDADE. $error");
                $response = array("success" => false, "message" => "Houve um erro ao excluir a atividade.");
                return json_encode($response); 

            }
        }

        public function deleteTicketById($SUP_IDSUPORTE)
        {       
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try {
                $sql = "UPDATE SUP_SUPORTE SET EXCLUIDO = '1' WHERE SUP_IDSUPORTE = :SUP_IDSUPORTE";
                $stmt = $this->pdoSistema->prepare($sql);            
                $stmt->bindParam(':SUP_IDSUPORTE', $SUP_IDSUPORTE, PDO::PARAM_STR);
                $stmt->execute();

                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O ticket com id $SUP_IDSUPORTE foi excluído com sucesso.");
                $response = array("success" => true, "message" => "Ticket excluído com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao excluir o ticket com id $SUP_IDSUPORTE. $error");
                $response = array("success" => false, "message" => "Houve um erro ao excluir o ticket.");
                return json_encode($response); 

            }
        }

        public function deletePropostaById($ADM_IDPRO_PROPOSTAS)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }
            
            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "UPDATE ADM_PRO_PROPOSTAS SET EXCLUIDO = '1', PRO_DTUPDATE = :PRO_DTUPDATE WHERE ADM_IDPRO_PROPOSTAS = :ADM_IDPRO_PROPOSTAS";
                $stmt = $this->pdo->prepare($sql);            
                $stmt->bindParam(':ADM_IDPRO_PROPOSTAS', $ADM_IDPRO_PROPOSTAS, PDO::PARAM_STR);
                $stmt->bindParam(':PRO_DTUPDATE', $DATA, PDO::PARAM_STR);
                $stmt->execute();

                $response = array("success" => true, "message" => "Proposta excluída com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }

        public function deleteResumoFinanceiroById($RES_IDRESUMO_FINANCEIRO)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM RES_RESUMO_FINANCEIRO WHERE RES_IDRESUMO_FINANCEIRO = :RES_IDRESUMO_FINANCEIRO";
                $stmt = $this->pdo->prepare($sql);            
                $stmt->bindParam(':RES_IDRESUMO_FINANCEIRO', $RES_IDRESUMO_FINANCEIRO, PDO::PARAM_STR);
                $stmt->execute();

                $response = array("success" => true, "message" => "Dado financeiro excluído com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $response = array("success" => false, "message" => "Não foi possível excluir o dado financeiro no banco de dados.");
                return json_encode($response); 
            }
        }

        public function deleteMembroById($MOR_IDMORADORES)
        {       
            if (!$this->pdo) {$this->conexao();}

            try {
                $sql = "DELETE FROM MOR_MORADORES WHERE MOR_IDMORADORES = :MOR_IDMORADORES";
                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':MOR_IDMORADORES', $MOR_IDMORADORES, PDO::PARAM_STR);
                $stmt->execute();
                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O membro da família com id $MOR_IDMORADORES foi excluído com sucesso.");  
                return "Membro excluído com sucesso.";
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A exclusão do membro da família com id $MOR_IDMORADORES falhou: $erro");  
                return $e->getMessage();
            }
        }

        public function deleteFornecedorById($FOR_IDFORNECEDOR)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "UPDATE ADM_FOR_FORNECEDOR SET EXCLUIDO = '1' WHERE FOR_IDFORNECEDOR = :FOR_IDFORNECEDOR";
                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindParam(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->execute();

                $response = array("success" => true, "message" => "Prestador excluído com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $response = array("success" => false, "message" => "Erro ao ecluir prestador do banco de dados.");
                return json_encode($response); 
            }
        }

        public function deleteOSById($MAE_IDMANUTENCAO_ATIVIDADE_EXEC)
        {
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {
                $sql = "SELECT * FROM ADM_MAE_MANUTENCAO_ATIVIDADE_EXEC WHERE MAE_IDMANUTENCAO_ATIVIDADE_EXEC = :MAE_IDMANUTENCAO_ATIVIDADE_EXEC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAE_IDMANUTENCAO_ATIVIDADE_EXEC', $MAE_IDMANUTENCAO_ATIVIDADE_EXEC, PDO::PARAM_STR);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                $sql = "DELETE FROM ADM_MAE_MANUTENCAO_ATIVIDADE_EXEC WHERE MAE_IDMANUTENCAO_ATIVIDADE_EXEC = :MAE_IDMANUTENCAO_ATIVIDADE_EXEC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAE_IDMANUTENCAO_ATIVIDADE_EXEC', $MAE_IDMANUTENCAO_ATIVIDADE_EXEC, PDO::PARAM_STR);
                $stmt->execute();
            
                return $resultados;
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao excluir a OS com id $MAE_IDMANUTENCAO_ATIVIDADE_EXEC: $error");
                return "0";
            }
        }

        public function deleteOSAutoById($MAN_IDMANUTENCAO_ATIVIDADE)
        {
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {           
                $sql = "DELETE FROM ADM_MAE_MANUTENCAO_ATIVIDADE_EXEC 
                        WHERE MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE
                        AND MAE_STSTATUS = 'PROGRAMADA'";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->execute();
            
                $response = array("success" => true, "message" => "As OS's programadas foram excluídas com sucesso.");
                return json_encode($response); 
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao excluir a OS com id $MAN_IDMANUTENCAO_ATIVIDADE: $error");
                $response = array("success" => false, "message" => "Houve um erro ao excluir as OS's programadas.");
                return json_encode($response); 
            }
        }

        public function getAtivOSById($MAN_IDMANUTENCAO_ATIVIDADE)
        {
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {
                $sql = "SELECT * FROM ADM_MAE_MANUTENCAO_ATIVIDADE_EXEC 
                        WHERE MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE
                        AND MAE_STSTATUS = 'PROGRAMADA'";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao verificar as OS da atividade com id $MAN_IDMANUTENCAO_ATIVIDADE: $error");
                return "0";
            }
        }


        public function deletePendenciaInfo($EPE_IDEVOLUCAO_PENDENCIA)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM EPE_EVOLUCAO_PENDENCIA WHERE EPE_IDEVOLUCAO_PENDENCIA = :EPE_IDEVOLUCAO_PENDENCIA";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':EPE_IDEVOLUCAO_PENDENCIA', $EPE_IDEVOLUCAO_PENDENCIA, PDO::PARAM_STR);
                $stmt->execute();            

                $response = array("success" => true, "message" => "Pendência deletada com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $response = array("success" => false, "message" => "Erro ao ecluir prestador do banco de dados. $erro");
                return json_encode($response); 
            }
        }

        public function deleteReclamacaoInfo($REC_IDRECLAMACAO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM REC_RECLAMACAO WHERE REC_IDRECLAMACAO = :REC_IDRECLAMACAO";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':REC_IDRECLAMACAO', $REC_IDRECLAMACAO, PDO::PARAM_STR);
                $stmt->execute();
            
                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A mensagem com ID $REC_IDRECLAMACAO foi excluída.");
                return ["success" => "Mensagem deletada com sucesso."];
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A exlusão da mensagem com ID $REC_IDRECLAMACAO falhou: $erro"); 
                return ["error" => $e->getMessage()];
            }
        }

        public function deletePetInfo($PEM_IDPETMORADOR)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM PEM_PETMORADOR WHERE PEM_IDPETMORADOR = :PEM_IDPETMORADOR";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PEM_IDPETMORADOR', $PEM_IDPETMORADOR, PDO::PARAM_STR);
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Pet deletado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }


        public function deleteEncomenda($ENC_IDENCOMENDA)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM ENC_ENCOMENDA WHERE ENC_IDENCOMENDA = :ENC_IDENCOMENDA";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':ENC_IDENCOMENDA', $ENC_IDENCOMENDA, PDO::PARAM_STR);
                $stmt->execute();
            
                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A encomenda com id $ENC_IDENCOMENDA foi deletada."); 
                return ["success" => "Encomenda deletada com sucesso."];
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A exclusão da encomenda com id $ENC_IDENCOMENDA falhou.: $erro"); 
                return ["error" => $e->getMessage()];
            }
        }

        public function deleteVisitaById($VIS_IDVISITANTE)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM VIS_VISITANTE WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Visitante deletado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function deleteCampanhaPub($PDS_IDPRESTADOR_SERVICO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

               $now = new DateTime(); 
                $PDS_DTLASTUPDATE = $now->format('Y-m-d H:i:s');

            try {
                $sql = "UPDATE PDS_PUBLICIDADE SET EXCLUIDO = '1', PDS_DTLASTUPDATE = :PDS_DTLASTUPDATE WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                 $stmt->bindParam(':PDS_DTLASTUPDATE', $PDS_DTLASTUPDATE, PDO::PARAM_STR);
                $stmt->execute();

                $this->insertLogInfo("Delete", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Campanha excluída com sucesso.");
                $response = array("success" => true, "message" => "Campanha excluída com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao excluir a campanha com id $PDS_IDPRESTADOR_SERVICO. $error");
                $response = array("success" => false, "message" => "Houve um erro ao excluir a campanha.");
                return json_encode($response); 
            }
        }

        public function deleteNotificacoesbyUser($USU_IDUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM USN_NOTIFICACAO WHERE USU_IDUSUARIO = :USU_IDUSUARIO";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Notificacão deletada com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }


        public function deleteReport($CON_DCMES_COMPETENCIA_USUARIO, $CON_DCANO_COMPETENCIA_USUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "DELETE FROM CON_CONCILIACAO 
                WHERE CON_DCMES_COMPETENCIA_USUARIO = :CON_DCMES_COMPETENCIA_USUARIO AND 
                    CON_DCANO_COMPETENCIA_USUARIO = :CON_DCANO_COMPETENCIA_USUARIO";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':CON_DCANO_COMPETENCIA_USUARIO', $CON_DCANO_COMPETENCIA_USUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':CON_DCMES_COMPETENCIA_USUARIO', $CON_DCMES_COMPETENCIA_USUARIO, PDO::PARAM_STR);
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Relatório deletado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function insertAvaliacaoPrestadorInfo($PDS_IDPRESTADOR_SERVICO, $APS_DCCOMENTARIO, $APS_NMNOTA, $USU_IDUSUARIO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO APS_AVALIACAO_PRESTADOR 
                        (PDS_IDPRESTADOR_SERVICO, APS_DCCOMENTARIO, APS_NMNOTA, USU_IDUSUARIO, APS_DTAVAL) 
                        VALUES (:PDS_IDPRESTADOR_SERVICO, :APS_DCCOMENTARIO, :APS_NMNOTA, :USU_IDUSUARIO, :APS_DTAVAL)";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':APS_DCCOMENTARIO', $APS_DCCOMENTARIO, PDO::PARAM_STR);
                $stmt->bindParam(':APS_NMNOTA', $APS_NMNOTA, PDO::PARAM_STR);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->bindParam(':APS_DTAVAL', $DATA, PDO::PARAM_STR); 
                
            
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Avaliação cadastrada com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function insertPrestadorInfo($FOR_DCNOME, $FOR_DCTELEFONE, $CAF_IDCATEGORIA_FORNECEDOR, $FOR_STSTATUS, $FOR_DCNOME_CONTATO, $FOR_DCDESC_ATIVIDADE, $FOR_DCCPF, $FOR_DCCNPJ, $FOR_DCEMAIL, $FOR_DCCEP, $FOR_DCENDERECO, $FOR_DCEND_NUMERO, $FOR_DCBAIRRO, $FOR_DCCIDADE, $FOR_DCESTADO, $FOR_DCFOTO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO ADM_FOR_FORNECEDOR (
                        FOR_DCNOME,
                        FOR_DCTELEFONE,
                        CAF_IDCATEGORIA_FORNECEDOR,
                        FOR_STSTATUS,
                        FOR_DCNOME_CONTATO,
                        FOR_DCDESC_ATIVIDADE,
                        FOR_DCCPF,
                        FOR_DCCNPJ,
                        FOR_DCEMAIL,
                        FOR_DCCEP,
                        FOR_DCENDERECO,
                        FOR_DCEND_NUMERO,
                        FOR_DCBAIRRO,
                        FOR_DCCIDADE,
                        FOR_DCESTADO,
                        FOR_DCFOTO,
                        FOR_DTCADASTRO
                    ) VALUES (
                        :FOR_DCNOME,
                        :FOR_DCTELEFONE,
                        :CAF_IDCATEGORIA_FORNECEDOR,
                        :FOR_STSTATUS,
                        :FOR_DCNOME_CONTATO,
                        :FOR_DCDESC_ATIVIDADE,
                        :FOR_DCCPF,
                        :FOR_DCCNPJ,
                        :FOR_DCEMAIL,
                        :FOR_DCCEP,
                        :FOR_DCENDERECO,
                        :FOR_DCEND_NUMERO,
                        :FOR_DCBAIRRO,
                        :FOR_DCCIDADE,
                        :FOR_DCESTADO,
                        :FOR_DCFOTO,
                        :FOR_DTCADASTRO
                    )
                    ";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindValue(':FOR_DCNOME', $FOR_DCNOME, PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCTELEFONE', $FOR_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindValue(':CAF_IDCATEGORIA_FORNECEDOR', $CAF_IDCATEGORIA_FORNECEDOR, PDO::PARAM_INT);
                $stmt->bindValue(':FOR_STSTATUS', $FOR_STSTATUS, PDO::PARAM_INT);
                $stmt->bindValue(':FOR_DCNOME_CONTATO', $FOR_DCNOME_CONTATO, is_null($FOR_DCNOME_CONTATO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCDESC_ATIVIDADE', $FOR_DCDESC_ATIVIDADE, is_null($FOR_DCDESC_ATIVIDADE) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCPF', $FOR_DCCPF, is_null($FOR_DCCPF) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCNPJ', $FOR_DCCNPJ, is_null($FOR_DCCNPJ) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCEMAIL', $FOR_DCEMAIL, is_null($FOR_DCEMAIL) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCEP', $FOR_DCCEP, is_null($FOR_DCCEP) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCENDERECO', $FOR_DCENDERECO, is_null($FOR_DCENDERECO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCEND_NUMERO', $FOR_DCEND_NUMERO, is_null($FOR_DCEND_NUMERO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCBAIRRO', $FOR_DCBAIRRO, is_null($FOR_DCBAIRRO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCIDADE', $FOR_DCCIDADE, is_null($FOR_DCCIDADE) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCESTADO', $FOR_DCESTADO, is_null($FOR_DCESTADO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCFOTO', $FOR_DCFOTO, is_null($FOR_DCFOTO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DTCADASTRO', $DATA, PDO::PARAM_STR);            
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Prestador cadastrado com sucesso.");
                return json_encode($response);   

            } catch (PDOException $e) {
                $response = array("success" => false, "message" => "Erro ao cadastrar prestador no banco de dados.");
                return json_encode($response); 
            }
        }

        public function insertResumoFinanceiroInfo($RES_DCTITULO, $RES_DTCOMPETENCIA, $RES_DTRESUMO, $RES_NMVALOR, $RES_NMQUANTIDADE_CONDO_PAGO, $RES_DCRESP_SINDICO, $RES_DCOBS)
        {  
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO RES_RESUMO_FINANCEIRO (
                        RES_DCTITULO,
                        RES_DTCOMPETENCIA,
                        RES_DTRESUMO,
                        RES_NMVALOR,
                        RES_NMQUANTIDADE_CONDO_PAGO,
                        RES_DCOBS,
                        RES_DTINSERT,
                        RES_DCRESP_SINDICO
                    ) VALUES (
                        :RES_DCTITULO,
                        :RES_DTCOMPETENCIA,
                        :RES_DTRESUMO,
                        :RES_NMVALOR,
                        :RES_NMQUANTIDADE_CONDO_PAGO,
                        :RES_DCOBS,
                        :RES_DTINSERT,
                        :RES_DCRESP_SINDICO
                    )
                    ";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindValue(':RES_DCTITULO', $RES_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':RES_DTCOMPETENCIA', $RES_DTCOMPETENCIA, PDO::PARAM_STR);
                $stmt->bindValue(':RES_DTRESUMO', $RES_DTRESUMO, PDO::PARAM_STR);
                $stmt->bindValue(':RES_NMQUANTIDADE_CONDO_PAGO', $RES_NMQUANTIDADE_CONDO_PAGO, PDO::PARAM_STR);
                $stmt->bindValue(':RES_NMVALOR', $RES_NMVALOR, PDO::PARAM_STR);
                $stmt->bindValue(':RES_DCOBS', $RES_DCOBS, is_null($RES_DCOBS) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':RES_DTINSERT', $DATA, PDO::PARAM_STR);    
                $stmt->bindValue(':RES_DCRESP_SINDICO', $RES_DCRESP_SINDICO, PDO::PARAM_STR);   
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Resumo financeiro cadastrado com sucesso.");
                return json_encode($response);   

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $response = array("success" => false, "message" => "Erro ao cadastrar o resumo financeiro no banco de dados. $erro");
                return json_encode($response); 
            }
        }

        public function insertTaskInfo($MAN_DCTITULO, $MAN_DCCATEGORIA, $MAN_DCDESC, $MAN_DCFREQUENCIA, $MAN_DTINI, $MAN_DTFIM, $MAN_STSTATUS, $MAN_DCTIPO, $MAN_STDISP_PORTARIA)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s'); 

            try {
                $sql = "INSERT INTO ADM_MAN_MANUTENCAO_ATIVIDADE (
                        MAN_DCTITULO,
                        MAN_DCCATEGORIA,
                        MAN_DCDESC,
                        MAN_DCFREQUENCIA,
                        MAN_DTINI,
                        MAN_DTFIM,
                        MAN_STSTATUS,
                        MAN_DCTIPO,
                        MAN_STDISP_PORTARIA
                    ) VALUES (
                        :MAN_DCTITULO,
                        :MAN_DCCATEGORIA,
                        :MAN_DCDESC,
                        :MAN_DCFREQUENCIA,
                        :MAN_DTINI,
                        :MAN_DTFIM,
                        :MAN_STSTATUS,
                        :MAN_DCTIPO,
                        :MAN_STDISP_PORTARIA
                    )
                    ";

                $stmt = $this->pdo->prepare($sql);
            
                $stmt->bindValue(':MAN_DCTITULO', $MAN_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCCATEGORIA', $MAN_DCCATEGORIA, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCDESC', $MAN_DCDESC, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCFREQUENCIA', $MAN_DCFREQUENCIA, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DTINI', $MAN_DTINI, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DTFIM', $MAN_DTFIM, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_STSTATUS', $MAN_STSTATUS, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCTIPO', $MAN_DCTIPO, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_STDISP_PORTARIA', $MAN_STDISP_PORTARIA, PDO::PARAM_STR);

                $stmt->execute();
            
                $response = array("success" => true, "message" => "Atividade cadastrada com sucesso.");
                return json_encode($response);   

            } catch (PDOException $e) {
                $response = array("success" => true, "message" => "Erro ao cadastrar a atividade no banco de dados.");
                return json_encode($response); 
            }
        }

        public function insertOSAutomatico($id, $frequencia, $dtini, $dtfim)
        {       
            if (!$this->pdo) {$this->conexao();}

            $inicio = new DateTime($dtini);
            $fim = new DateTime($dtfim);

            // Captura o horário (HH:mm:ss) do dtIni
            $hora = $inicio->format('H:i:s');

            $datas = [];

            // Define o intervalo com base na frequência
            switch (strtoupper($frequencia)) {
                case 'DIARIO':
                    $intervalo = new DateInterval('P1D');
                    break;
                case 'SEMANAL':
                    $intervalo = new DateInterval('P1W');
                    break;
                case 'QUINZENAL':
                    $intervalo = new DateInterval('P15D');
                    break;
                case 'MENSAL':
                    $intervalo = new DateInterval('P1M');
                    break;
                case 'BIMESTRAL':
                    $intervalo = new DateInterval('P2M');
                    break;
                case 'TRIMESTRAL':
                    $intervalo = new DateInterval('P3M');
                    break;
                case 'SEMESTRAL':
                    $intervalo = new DateInterval('P6M');
                    break;
                case 'ANUAL':
                    $intervalo = new DateInterval('P1Y');
                    break;
                case 'BIENAL':
                    $intervalo = new DateInterval('P2Y');
                    break;
                case 'TRIENAL':
                    $intervalo = new DateInterval('P3Y');
                    break;
                case 'QUINQUENAL':
                    $intervalo = new DateInterval('P5Y');
                    break;
                case 'UNICA':
                default:
                    return [$inicio->format('Y-m-d H:i:s')];
            }

        
            // Gera as datas com hora
            $periodo = new DatePeriod($inicio, $intervalo, (clone $fim)->modify('+1 day')); // inclui o fim
            foreach ($periodo as $data) {
                $dataComHora = $data->format('Y-m-d') . ' ' . $hora;
                $datas[] = $dataComHora;
            }

            foreach ($datas as $dataProgramada) {
                $MAE_DCOBS = "";
                $MAE_DTPROGRAMADA = $dataProgramada;
                $MAE_STSTATUS = "PROGRAMADA";
                $USU_IDUSUARIO = NULL;
                $FOR_IDFORNECEDOR = NULL;
                $MAE_DCFOTO_ANTES = ""; 
                $MAE_DCFOTO_DURANTE = "";
                $MAE_DCFOTO_DEPOIS = "";

                $jsonResult = $this->insertOSCadInfo(
                    $id,
                    $MAE_DCOBS,
                    $MAE_DTPROGRAMADA,
                    $MAE_STSTATUS,
                    $USU_IDUSUARIO,
                    $FOR_IDFORNECEDOR,
                    $MAE_DCFOTO_ANTES,
                    $MAE_DCFOTO_DURANTE,
                    $MAE_DCFOTO_DEPOIS
                );

                    $result = json_decode($jsonResult, true); 

                    if (!isset($result['success']) || !$result['success']) {
                        $response = array("success" => false, "message" => "Houve um erro durante a criação automática de OS.");
                        return json_encode($response);                         
                    }
            }

            $response = array("success" => true, "message" => "Todas as OS's foram criadas com sucesso.");
            return json_encode($response);    
           
        }

        public function updatePrestadorInfo($FOR_IDFORNECEDOR, $FOR_DCNOME, $FOR_DCTELEFONE, $CAF_IDCATEGORIA_FORNECEDOR, $FOR_STSTATUS, $FOR_DCNOME_CONTATO, $FOR_DCDESC_ATIVIDADE, $FOR_DCCPF, $FOR_DCCNPJ, $FOR_DCEMAIL, $FOR_DCCEP, $FOR_DCENDERECO, $FOR_DCEND_NUMERO, $FOR_DCBAIRRO, $FOR_DCCIDADE, $FOR_DCESTADO, $FOR_DCFOTO)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
        
            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');
        
            try {
                // Monta o SQL básico
                $sql = "UPDATE ADM_FOR_FORNECEDOR SET
                    FOR_DCNOME = :FOR_DCNOME,
                    FOR_DCTELEFONE = :FOR_DCTELEFONE,
                    CAF_IDCATEGORIA_FORNECEDOR = :CAF_IDCATEGORIA_FORNECEDOR,
                    FOR_STSTATUS = :FOR_STSTATUS,
                    FOR_DCNOME_CONTATO = :FOR_DCNOME_CONTATO,
                    FOR_DCDESC_ATIVIDADE = :FOR_DCDESC_ATIVIDADE,
                    FOR_DCCPF = :FOR_DCCPF,
                    FOR_DCCNPJ = :FOR_DCCNPJ,
                    FOR_DCEMAIL = :FOR_DCEMAIL,
                    FOR_DCCEP = :FOR_DCCEP,
                    FOR_DCENDERECO = :FOR_DCENDERECO,
                    FOR_DCEND_NUMERO = :FOR_DCEND_NUMERO,
                    FOR_DCBAIRRO = :FOR_DCBAIRRO,
                    FOR_DCCIDADE = :FOR_DCCIDADE,
                    FOR_DCESTADO = :FOR_DCESTADO";
        
                // Só adiciona FOR_DCFOTO se não estiver vazio ou nulo
                if (!empty($FOR_DCFOTO)) {
                    $sql .= ",
                    FOR_DCFOTO = :FOR_DCFOTO";
                }
            
                $sql .= " WHERE FOR_IDFORNECEDOR = :FOR_IDFORNECEDOR";
            
                $stmt = $this->pdo->prepare($sql);
            
                // Bind dos valores
                $stmt->bindValue(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCNOME', $FOR_DCNOME, PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCTELEFONE', $FOR_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindValue(':CAF_IDCATEGORIA_FORNECEDOR', $CAF_IDCATEGORIA_FORNECEDOR, PDO::PARAM_INT);
                $stmt->bindValue(':FOR_STSTATUS', $FOR_STSTATUS, PDO::PARAM_INT);
                $stmt->bindValue(':FOR_DCNOME_CONTATO', $FOR_DCNOME_CONTATO, is_null($FOR_DCNOME_CONTATO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCDESC_ATIVIDADE', $FOR_DCDESC_ATIVIDADE, is_null($FOR_DCDESC_ATIVIDADE) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCPF', $FOR_DCCPF, is_null($FOR_DCCPF) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCNPJ', $FOR_DCCNPJ, is_null($FOR_DCCNPJ) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCEMAIL', $FOR_DCEMAIL, is_null($FOR_DCEMAIL) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCEP', $FOR_DCCEP, is_null($FOR_DCCEP) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCENDERECO', $FOR_DCENDERECO, is_null($FOR_DCENDERECO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCEND_NUMERO', $FOR_DCEND_NUMERO, is_null($FOR_DCEND_NUMERO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCBAIRRO', $FOR_DCBAIRRO, is_null($FOR_DCBAIRRO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCCIDADE', $FOR_DCCIDADE, is_null($FOR_DCCIDADE) ? PDO::PARAM_NULL : PDO::PARAM_STR);
                $stmt->bindValue(':FOR_DCESTADO', $FOR_DCESTADO, is_null($FOR_DCESTADO) ? PDO::PARAM_NULL : PDO::PARAM_STR);
            
                // Só faz o bind de FOR_DCFOTO se ele tiver valor
                if (!empty($FOR_DCFOTO)) {
                    $stmt->bindValue(':FOR_DCFOTO', $FOR_DCFOTO, PDO::PARAM_STR);
                }
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Prestador atualizado com sucesso.");
                return json_encode($response);   
            
            } catch (PDOException $e) {
                $response = array("success" => false, "message" => "Erro ao atualizar prestador no banco de dados.");
                return json_encode($response); 
            }
        }

        public function updatePropostaComerciaStatuslInfo($ADM_IDPRO_PROPOSTAS, $PRO_DCSTATUS)
        {    
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
       
            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');
        
            try {
                // Monta o SQL básico
                $sql = "UPDATE ADM_PRO_PROPOSTAS SET             
                    PRO_DCSTATUS = :PRO_DCSTATUS";
                    
                $sql .= " WHERE ADM_IDPRO_PROPOSTAS = :ADM_IDPRO_PROPOSTAS";
            
                $stmt = $this->pdo->prepare($sql);
            
                // Bind dos valores
                 $stmt->bindValue(':PRO_DCSTATUS', $PRO_DCSTATUS, PDO::PARAM_STR); 
                $stmt->bindValue(':ADM_IDPRO_PROPOSTAS', $ADM_IDPRO_PROPOSTAS, PDO::PARAM_STR);              
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Status da proposta comercial atualizada com sucesso.");
                return json_encode($response);   
            
            } catch (PDOException $e) {
                $error = $e->getMessage();                
                $response = array("success" => false, "message" => "Erro ao atualizar a proposta comercial. $error");
                $this->insertLogInfo("Error", $PRO_NMRESPONSAVEL, "N/A",  $this->BANCODEDADOS_CONDOMINIO,"Erro ao atualizar a proposta comercial $ADM_IDPRO_PROPOSTAS.: $error");  
                return json_encode($response); 
            }
        }


        public function updatePropostaComercialInfo($ADM_IDPRO_PROPOSTAS, $PRO_NMRESPONSAVEL, $PRO_DCPRAZO_EXECUCAO_DIAS, $PRO_NMVALOR, $PRO_DTVALIDADE_PROPOSTA, $PRO_DCDESC, $PRO_DCFOTO, $PRO_DCARQUIVO)
        {    
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
       
            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            $PRO_DCSTATUS = "EM ANALISE";
        
            try {
                // Monta o SQL básico
                $sql = "UPDATE ADM_PRO_PROPOSTAS SET
                    PRO_NMRESPONSAVEL = :PRO_NMRESPONSAVEL,
                    PRO_DCPRAZO_EXECUCAO_DIAS = :PRO_DCPRAZO_EXECUCAO_DIAS,
                    PRO_NMVALOR = :PRO_NMVALOR,
                    PRO_DTVALIDADE_PROPOSTA = :PRO_DTVALIDADE_PROPOSTA,
                    PRO_DCDESC = :PRO_DCDESC,
                    PRO_DCFOTO = :PRO_DCFOTO,
                    PRO_DTRESPOSTA = :PRO_DTRESPOSTA,
                    PRO_DCSTATUS = :PRO_DCSTATUS,
                    PRO_DCARQUIVO = :PRO_DCARQUIVO";
                    
                $sql .= " WHERE ADM_IDPRO_PROPOSTAS = :ADM_IDPRO_PROPOSTAS";
            
                $stmt = $this->pdo->prepare($sql);
            
                // Bind dos valores
                $stmt->bindValue(':PRO_NMRESPONSAVEL', $PRO_NMRESPONSAVEL, PDO::PARAM_STR);
                $stmt->bindValue(':PRO_DCPRAZO_EXECUCAO_DIAS', $PRO_DCPRAZO_EXECUCAO_DIAS, PDO::PARAM_STR);
                $stmt->bindValue(':PRO_NMVALOR', $PRO_NMVALOR, PDO::PARAM_STR);
                $stmt->bindValue(':PRO_DTVALIDADE_PROPOSTA', $PRO_DTVALIDADE_PROPOSTA, PDO::PARAM_STR);
                $stmt->bindValue(':PRO_DCDESC', $PRO_DCDESC, PDO::PARAM_STR);
                $stmt->bindValue(':PRO_DCFOTO', $PRO_DCFOTO, PDO::PARAM_STR);
                $stmt->bindValue(':PRO_DCARQUIVO', $PRO_DCARQUIVO, PDO::PARAM_STR);    
                $stmt->bindValue(':PRO_DTRESPOSTA', $DATA, PDO::PARAM_STR); 
                $stmt->bindValue(':PRO_DCSTATUS', $PRO_DCSTATUS, PDO::PARAM_STR); 
                $stmt->bindValue(':ADM_IDPRO_PROPOSTAS', $ADM_IDPRO_PROPOSTAS, PDO::PARAM_STR);              
            
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Proposta Comercial enviada com sucesso.");
                return json_encode($response);   
            
            } catch (PDOException $e) {
                $error = $e->getMessage();                
                $response = array("success" => false, "message" => "Erro ao enviar a proposta comercial. $error");
                $this->insertLogInfo("Error", $PRO_NMRESPONSAVEL, "N/A",  $this->BANCODEDADOS_CONDOMINIO,"Erro ao enviar a proposta comercial.: $error");  
                return json_encode($response); 
            }
        }


        public function updatePrestadorRating($FOR_IDFORNECEDOR, $FOR_NMRATING)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao(); 
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "UPDATE ADM_FOR_FORNECEDOR SET
                FOR_NMRATING = :FOR_NMRATING
            WHERE
                FOR_IDFORNECEDOR = :FOR_IDFORNECEDOR";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue(':FOR_IDFORNECEDOR', $FOR_IDFORNECEDOR, PDO::PARAM_STR);
                $stmt->bindValue(':FOR_NMRATING', $FOR_NMRATING, PDO::PARAM_STR);
                
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Rating do prestador atualizado com sucesso.");
                return json_encode($response);   

            } catch (PDOException $e) {
                $response = array("success" => true, "message" => "Erro ao atualizar prestador no banco de dados.");
                return json_encode($response); 
            }
        }

        public function updateAtividadeInfo($MAN_IDMANUTENCAO_ATIVIDADE, $MAN_DCCATEGORIA, $MAN_DCTITULO, $MAN_DCDESC, $MAN_DCFREQUENCIA, $MAN_DTINI, $MAN_DTFIM, $MAN_STSTATUS, $MAN_DCTIPO, $MAN_STDISP_PORTARIA)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "UPDATE ADM_MAN_MANUTENCAO_ATIVIDADE SET
                MAN_DCTITULO = :MAN_DCTITULO,
                MAN_DCCATEGORIA = :MAN_DCCATEGORIA,
                MAN_DCDESC = :MAN_DCDESC,
                MAN_DCFREQUENCIA = :MAN_DCFREQUENCIA,
                MAN_DTINI = :MAN_DTINI,
                MAN_DTFIM = :MAN_DTFIM,
                MAN_STSTATUS = :MAN_STSTATUS,
                MAN_DCTIPO = :MAN_DCTIPO,
                MAN_STDISP_PORTARIA = :MAN_STDISP_PORTARIA
            WHERE
                MAN_IDMANUTENCAO_ATIVIDADE = :MAN_IDMANUTENCAO_ATIVIDADE";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue(':MAN_IDMANUTENCAO_ATIVIDADE', $MAN_IDMANUTENCAO_ATIVIDADE, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCTITULO', $MAN_DCTITULO, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCCATEGORIA', $MAN_DCCATEGORIA, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCDESC', $MAN_DCDESC, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCFREQUENCIA', $MAN_DCFREQUENCIA, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DTINI', $MAN_DTINI, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DTFIM', $MAN_DTFIM, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_STSTATUS', $MAN_STSTATUS, PDO::PARAM_STR);
                $stmt->bindValue(':MAN_DCTIPO', $MAN_DCTIPO, PDO::PARAM_STR);      
                $stmt->bindValue(':MAN_STDISP_PORTARIA', $MAN_STDISP_PORTARIA, PDO::PARAM_STR);              
        
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Atividade atualizada com sucesso.");
                return json_encode($response);   

            } catch (PDOException $e) {
                $response = array("success" => true, "message" => "Erro ao atualizar a atividade no banco de dados.");
                return json_encode($response); 
            }
        }

        public function updateConvidadoListaInfo($LIS_DCNOME, $LIS_DCDOCUMENTO, $LIS_STSTATUS, $LIS_IDLISTACONVIDADOS)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "UPDATE LIS_LISTACONVIDADOS 
                        SET LIS_DCNOME = :LIS_DCNOME,
                        LIS_DCDOCUMENTO = :LIS_DCDOCUMENTO,
                        LIS_STSTATUS = :LIS_STSTATUS
                        WHERE LIS_IDLISTACONVIDADOS = :LIS_IDLISTACONVIDADOS";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':LIS_DCNOME', $LIS_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_IDLISTACONVIDADOS', $LIS_IDLISTACONVIDADOS, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_DCDOCUMENTO', $LIS_DCDOCUMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_STSTATUS', $LIS_STSTATUS, PDO::PARAM_STR);
                
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Visitante atualizado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function updatePublicidade($PDS_IDPRESTADOR_SERVICO, $PDS_STONAIR)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "UPDATE PDS_PUBLICIDADE 
                        SET PDS_STONAIR = :PDS_STONAIR
                        WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_STONAIR', $PDS_STONAIR, PDO::PARAM_STR);
               
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Publicidade atualizada com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function updateCreditoWhatsappInfo($CFG_DCVALOR)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {
                $sql = "UPDATE CFG_CONFIGURACAO 
                        SET CFG_DCVALOR = :CFG_DCVALOR
                        WHERE CFG_DCPARAMETRO = 'WHATSAPP_TOTAL_MSG'";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':CFG_DCVALOR', $CFG_DCVALOR, PDO::PARAM_STR);
                
                $stmt->execute();
            
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Creditos atualizados com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function updateConfigInfo($CFG_DCPARAMETRO, $CFG_DCVALOR)
        {       

            if (!$this->pdo) {
                $this->conexao();
            }

            if ($CFG_DCPARAMETRO == "VAGAS_VISITANTES") {
              
                $sql = "SELECT COUNT(*) FROM VGA_VAGAS_VISITANTE WHERE VGA_STSTATUS = 'OCUPADA'";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $vagasOcupadas = $stmt->fetchColumn();
            
                if ($vagasOcupadas > 0) {
                    return json_encode(["status" => "error", "message" => 'Quantidade de vagas não pode ser alterada, pois há veículos estacionados no momento.']);
                    //return "Quantidade de vagas não pode ser alterada, pois há veículos estacionados no momento.";
                }
            
                $this->pdo->beginTransaction();
            
                try {
                    $sql = "DELETE FROM VGA_VAGAS_VISITANTE";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute();
            
                    $sql = "INSERT INTO VGA_VAGAS_VISITANTE (VGA_NMVAGA, VGA_STSTATUS) VALUES (:VGA_NMVAGA, :VGA_STSTATUS)";
                    $stmt = $this->pdo->prepare($sql);
                    
                    $VGA_STSTATUS = "LIVRE";
            
                    for ($aux = 1; $aux <= $CFG_DCVALOR; $aux++) {
                        $VGA_NMVAGA = $aux; 
                        $stmt->bindValue(':VGA_NMVAGA', $VGA_NMVAGA, PDO::PARAM_STR);
                        $stmt->bindValue(':VGA_STSTATUS', $VGA_STSTATUS, PDO::PARAM_STR);
                        $stmt->execute();
                    }
            
                       $this->pdo->commit();
            
                    //return "Vagas de visitantes redefinidas com sucesso!";
                    //return json_encode(["status" => "success", "message" => 'Vagas de visitantes redefinidas com sucesso!']);
                } catch (Exception $e) {
                    $this->pdo->rollBack();
                    return json_encode(["status" => "error", "message" => 'Erro ao redefinir as vagas']);
                }
            }            
            

            try {
                $sql = "UPDATE CFG_CONFIGURACAO 
                        SET CFG_DCVALOR = :CFG_DCVALOR
                        WHERE CFG_DCPARAMETRO = :CFG_DCPARAMETRO";

                $stmt = $this->pdo->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':CFG_DCVALOR', $CFG_DCVALOR, PDO::PARAM_STR);
                $stmt->bindParam(':CFG_DCPARAMETRO', $CFG_DCPARAMETRO, PDO::PARAM_STR);                
                $stmt->execute();
                
                return json_encode(["status" => "success", "message" => 'Configuração atualizada com sucesso.']);
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return json_encode(["status" => "error", "message" => 'A tentativa de atualização falhou.']);
            }
        }

        public function notifyEmail($SUBJECT, $MSG, $HOST)
        {
            $this->getParameterInfo();

            foreach($this->ARRAY_PARAMETERINFO as $value)
            {
                if($value["CFG_DCPARAMETRO"] == "EMAIL_ALERTAS")
                {
                    $emailTo = $value["CFG_DCVALOR"];
                }
            } 

            // Configurações do e-mail
            $to = $emailTo; 
            $subject = "ATENÇÃO: $SUBJECT";
            $body = "$MSG\n";

            // Adiciona cabeçalhos para o e-mail
            $headers = "From: no-reply@$HOST\r\n";
            $headers .= "Reply-To: no-reply@$HOST\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Define a codificação como UTF-8
            $headers .= "MIME-Version: 1.0\r\n";
            
            $result = mail($to, $subject, $body, $headers); 
            return $result;      
        }

        public function updateCheckboxConvidados($LIS_IDLISTACONVIDADOS, $LIS_STSTATUS)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE LIS_LISTACONVIDADOS SET LIS_STSTATUS = :LIS_STSTATUS WHERE LIS_IDLISTACONVIDADOS = :LIS_IDLISTACONVIDADOS";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':LIS_IDLISTACONVIDADOS', $LIS_IDLISTACONVIDADOS, PDO::PARAM_STR);
                $stmt->bindParam(':LIS_STSTATUS', $LIS_STSTATUS, PDO::PARAM_STR); 
                $stmt->execute();     

                return ["success" => "Visitante atualizado com sucesso."];

            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function updateCheckboxConvidadosLista($VIS_IDVISITANTE, $VIS_DCNOME, $VIS_STCONVIDADO_FESTA)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE VIS_VISITANTE 
                        SET VIS_STCONVIDADO_FESTA = :VIS_STCONVIDADO_FESTA 
                        WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
                $stmt->bindParam(':VIS_STCONVIDADO_FESTA', $VIS_STCONVIDADO_FESTA, PDO::PARAM_STR); 
                $stmt->execute();
            
                if ($stmt->rowCount() > 0) {
                    $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O visitante $VIS_DCNOME foi atualizado o status do salão de festas para $VIS_STCONVIDADO_FESTA com sucesso.");
                    return "Visitante atualizado com sucesso.";
                } else {                    
                    return "Nenhuma linha foi alterada. ID pode ser inválido ou valor já era o mesmo.";
                }
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao tentar atualizar o status do salão de festas do visitante $VIS_DCNOME para $VIS_STCONVIDADO_FESTA. $error");
                return ["error" => $e->getMessage()];
            }
        }

        public function liberarVagaInfoById($VGA_NMVAGA)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE VGA_VAGAS_VISITANTE 
                        SET VGA_STSTATUS = 'LIVRE',
                        VIS_IDVISITANTE = NULL,
                        VGA_DTENTRADA = NULL
                        WHERE VGA_NMVAGA = :VGA_NMVAGA";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VGA_NMVAGA', $VGA_NMVAGA, PDO::PARAM_STR);
                $stmt->execute();
            
                $response = array("success" => true, "message" => "Vaga liberada com sucesso.");
                $this->insertLogInfo("info", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Vaga $VGA_NMVAGA liberada com sucesso.");
                return json_encode($response); 
            
            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A liberação da vaga $VGA_NMVAGA falhou. $erro ");
                $response = array("success" => false, "message" => "A liberação da vaga falhou. Erro: $erro ");
                return json_encode($response); 
            }
        }

        public function updateCheckboxEncomendasMorador($ENC_IDENCOMENDA, $ENC_STENTREGA_MORADOR)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE ENC_ENCOMENDA 
                        SET ENC_STENTREGA_MORADOR = :ENC_STENTREGA_MORADOR 
                        WHERE ENC_IDENCOMENDA = :ENC_IDENCOMENDA 
                          AND (ENC_STENTREGA_MORADOR <> 'ENTREGUE' OR ENC_STENTREGA_MORADOR IS NULL)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_IDENCOMENDA', $ENC_IDENCOMENDA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_STENTREGA_MORADOR', $ENC_STENTREGA_MORADOR, PDO::PARAM_STR); 
                $stmt->execute();    

                $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Status da encomenda com id $ENC_IDENCOMENDA atualizado para $ENC_STENTREGA_MORADOR com sucesso.");

                return ["success" => "Encomenda atualizada com sucesso."];

            } catch (PDOException $e) {

                $erro = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Alteração do status da encomenda com id $ENC_IDENCOMENDA para $ENC_STENTREGA_MORADOR falhou.: $erro");  

                return ["error" => $e->getMessage()];
            }
        }

        public function updateCheckboxEncomendasMoradorByApi($ENC_DCHASHENTREGA)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $ENC_STENTREGA_MORADOR = "A RETIRAR";

            try
            {         
                $sql = "UPDATE ENC_ENCOMENDA SET ENC_STENTREGA_MORADOR = :ENC_STENTREGA_MORADOR WHERE ENC_DCHASHENTREGA = :ENC_DCHASHENTREGA";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_DCHASHENTREGA', $ENC_DCHASHENTREGA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_STENTREGA_MORADOR', $ENC_STENTREGA_MORADOR, PDO::PARAM_STR); 
                $stmt->execute();     

                return "1";

            } catch (PDOException $e) {
                return "0";
                //return ["error" => $e->getMessage()];
            }
        }

        public function getUserInfoEncomenda($ENC_DCHASHENTREGA) 
        {          
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {           
                $sql = "SELECT *
                        FROM ENC_ENCOMENDA 
                        WHERE ENC_DCHASHENTREGA = :ENC_DCHASHENTREGA";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_DCHASHENTREGA', $ENC_DCHASHENTREGA, PDO::PARAM_STR);
                $stmt->execute();
        
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) 
                {                    
                    return [
                        'ENC_IDENCOMENDA' => $result['ENC_IDENCOMENDA'],
                        'ENC_STENTREGA_MORADOR' => $result['ENC_STENTREGA_MORADOR']
                    ];
                } else { 
                    return "0";
                }
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }
        }

        public function insertUserInfo($USU_DCEMAIL, $USU_DCNOME, $CUS_DCBLOCO, $CUS_DCAPARTAMENTO, $CUS_DCFUNCAO, $USU_DCSENHA, $USU_DCTELEFONE, $USU_DCSEXO, $USU_DTNASCIMENTO, $USU_DCFOTO=" ")
        {       
            $this->pdoSistema = null; //detruindo objeto para evitar erro de busca do lastid do usuário
            $this->conexaoSistema();    

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO USU_USUARIO 
                        (USU_DCEMAIL, USU_DCNOME, USU_DCSENHA, USU_DTCADASTRO, USU_DCTELEFONE, USU_DCSEXO, USU_DTNASCIMENTO, USU_DCFOTO) 
                        VALUES (:USU_DCEMAIL, :USU_DCNOME, :USU_DCSENHA, :USU_DTCADASTRO, :USU_DCTELEFONE, :USU_DCSEXO, :USU_DTNASCIMENTO, :USU_DCFOTO)";

                $stmt = $this->pdoSistema->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':USU_DCEMAIL', $USU_DCEMAIL, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCSENHA', $USU_DCSENHA, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DTCADASTRO', $DATA, PDO::PARAM_STR); 
                $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCFOTO', $USU_DCFOTO, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCSEXO', $USU_DCSEXO, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DTNASCIMENTO', $USU_DTNASCIMENTO, PDO::PARAM_STR);
                
                $stmt->execute();                     

            } catch (PDOException $e) {
                return $e->getMessage();   
            }

            $USU_IDUSUARIO = $this->pdoSistema->lastInsertId();

            if($USU_IDUSUARIO)
            {
                try {
                    $sql = "INSERT INTO CUS_CONDOUSUARIO 
                            (USU_IDUSUARIO, CON_IDCONDOMINIO, CUS_DCBLOCO, CUS_DCAPARTAMENTO, CUS_DCFUNCAO) 
                            VALUES (:USU_IDUSUARIO, :CON_IDCONDOMINIO, :CUS_DCBLOCO, :CUS_DCAPARTAMENTO, :CUS_DCFUNCAO)";
    
                    $stmt = $this->pdoSistema->prepare($sql);
                
                    // Liga os parâmetros aos valores
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                    $stmt->bindParam(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCBLOCO', $CUS_DCBLOCO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCAPARTAMENTO', $CUS_DCAPARTAMENTO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCFUNCAO', $CUS_DCFUNCAO, PDO::PARAM_STR);
                    $stmt->execute();
    
                    $response = array("success" => true, "message" => "Usuário cadastrado com sucesso.");
                    return json_encode($response); 
    
                } catch (PDOException $e) {
                    $erro = $e->getMessage(); 
                    $response = array("success" => false, "message" => "$erro");
                    return json_encode($response);   
                }
            }

            
        }

        public function insertFuncionarioInfo($USU_DCEMAIL, $USU_DCSENHA, $USU_DCNOME, $CUS_DCFUNCAO, $USU_DCTELEFONE, $USU_DCFOTO=" ")
        {                   
            $this->pdoSistema = null; //detruindo objeto para evitar erro de busca do lastid do usuário
            $this->conexaoSistema();    

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {
                $sql = "INSERT INTO USU_USUARIO 
                        (USU_DCEMAIL, USU_DCNOME, USU_DCSENHA, USU_DTCADASTRO, USU_DCTELEFONE, USU_DCFOTO) 
                        VALUES (:USU_DCEMAIL, :USU_DCNOME, :USU_DCSENHA, :USU_DTCADASTRO, :USU_DCTELEFONE, :USU_DCFOTO)";

                $stmt = $this->pdoSistema->prepare($sql);
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':USU_DCEMAIL', $USU_DCEMAIL, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCSENHA', $USU_DCSENHA, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DTCADASTRO', $DATA, PDO::PARAM_STR); 
                $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCFOTO', $USU_DCFOTO, PDO::PARAM_STR);              

                $stmt->execute();                     

            } catch (PDOException $e) {
                return $e->getMessage();   
            }

            $USU_IDUSUARIO = $this->pdoSistema->lastInsertId();

            if($USU_IDUSUARIO)
            {
                try {
                    $sql = "INSERT INTO CUS_CONDOUSUARIO 
                            (USU_IDUSUARIO, CON_IDCONDOMINIO, CUS_DCFUNCAO) 
                            VALUES (:USU_IDUSUARIO, :CON_IDCONDOMINIO, :CUS_DCFUNCAO)";
    
                    $stmt = $this->pdoSistema->prepare($sql);
                
                    // Liga os parâmetros aos valores
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                    $stmt->bindParam(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCFUNCAO', $CUS_DCFUNCAO, PDO::PARAM_STR);
                    $stmt->execute();
    
                    $response = array("success" => true, "message" => "Funcionario cadastrado com sucesso.");
                    return json_encode($response); 
    
                } catch (PDOException $e) {
                    $erro = $e->getMessage(); 
                    $response = array("success" => false, "message" => "$erro");
                    return json_encode($response);   
                }
            }

            
        }

        public function updateUserInfoById($USU_IDUSUARIO, $USU_DCNOME, $USU_DCTELEFONE, $USU_DCSEXO, $USU_DTNASCIMENTO, $USU_DCFOTO="", $USU_DCSENHA="")
        {
            $this->pdoSistema = null; 
            $this->conexaoSistema();    

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {

                if ($USU_DCSENHA != "") {
                    // Se a senha for informada
                    $sql = "UPDATE USU_USUARIO SET 
                              USU_DCNOME = :USU_DCNOME,
                              USU_DCSENHA = :USU_DCSENHA,
                              USU_DCTELEFONE = :USU_DCTELEFONE,
                              USU_DCSEXO = :USU_DCSEXO,
                              USU_DTNASCIMENTO = :USU_DTNASCIMENTO";
                
                    // Se a foto também for enviada, atualiza a foto
                    if ($USU_DCFOTO != "") {
                        $sql .= ", USU_DCFOTO = :USU_DCFOTO";
                    }
                
                    $sql .= " WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                
                    $stmt = $this->pdoSistema->prepare($sql);
                    $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCSENHA', $USU_DCSENHA, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCSEXO', $USU_DCSEXO, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DTNASCIMENTO', $USU_DTNASCIMENTO, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                
                    if ($USU_DCFOTO != "") {
                        $stmt->bindParam(':USU_DCFOTO', $USU_DCFOTO, PDO::PARAM_STR);
                    }
                
                } else {
                    // Se a senha NÃO for informada
                    $sql = "UPDATE USU_USUARIO SET 
                              USU_DCNOME = :USU_DCNOME,
                              USU_DCTELEFONE = :USU_DCTELEFONE,
                              USU_DCSEXO = :USU_DCSEXO,
                              USU_DTNASCIMENTO = :USU_DTNASCIMENTO";
                
                    // Se a foto também for enviada, atualiza a foto
                    if ($USU_DCFOTO != "") {
                        $sql .= ", USU_DCFOTO = :USU_DCFOTO";
                    }
                
                    $sql .= " WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                
                    $stmt = $this->pdoSistema->prepare($sql);
                    $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCSEXO', $USU_DCSEXO, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DTNASCIMENTO', $USU_DTNASCIMENTO, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                
                    if ($USU_DCFOTO != "") {
                        $stmt->bindParam(':USU_DCFOTO', $USU_DCFOTO, PDO::PARAM_STR);
                    }
                }               
                
                $stmt->execute(); 
                
                $this->insertLogInfo("Update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O usuário $USU_DCNOME foi atualizado com sucesso");  
                $response = array("success" => true, "message" => "Usuário atualizado com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage(); 
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A atualização do usuário $USU_DCNOME falhou: $erro"); 
                $response = array("success" => false, "message" => "$erro");
                return json_encode($response);  
            }          
        }

        public function updateFuncionarioInfoById($USU_IDUSUARIO, $USU_DCNOME, $USU_DCTELEFONE, $USU_DCFOTO="", $USU_DCSENHA="")
        { 
            $this->pdoSistema = null; 
            $this->conexaoSistema();    

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

            try {

                if ($USU_DCSENHA != "") {
                    // Se a senha for informada
                    $sql = "UPDATE USU_USUARIO SET 
                              USU_DCNOME = :USU_DCNOME,
                              USU_DCSENHA = :USU_DCSENHA,
                              USU_DCTELEFONE = :USU_DCTELEFONE";
                
                    // Se a foto também for enviada, atualiza a foto
                    if ($USU_DCFOTO != "") {
                        $sql .= ", USU_DCFOTO = :USU_DCFOTO";
                    }
                
                    $sql .= " WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                
                    $stmt = $this->pdoSistema->prepare($sql);
                    $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCSENHA', $USU_DCSENHA, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                
                    if ($USU_DCFOTO != "") {
                        $stmt->bindParam(':USU_DCFOTO', $USU_DCFOTO, PDO::PARAM_STR);
                    }
                
                } else {
                    // Se a senha NÃO for informada
                    $sql = "UPDATE USU_USUARIO SET 
                              USU_DCNOME = :USU_DCNOME,
                              USU_DCTELEFONE = :USU_DCTELEFONE";
                
                    // Se a foto também for enviada, atualiza a foto
                    if ($USU_DCFOTO != "") {
                        $sql .= ", USU_DCFOTO = :USU_DCFOTO";
                    }
                
                    $sql .= " WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                
                    $stmt = $this->pdoSistema->prepare($sql);
                    $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                
                    if ($USU_DCFOTO != "") {
                        $stmt->bindParam(':USU_DCFOTO', $USU_DCFOTO, PDO::PARAM_STR);
                    }
                }               
                
                $stmt->execute(); 
                
                $response = array("success" => true, "message" => "Funcionário atualizado com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage(); 
                $response = array("success" => false, "message" => "$erro");
                return json_encode($response);  
            }          
        }

        public function insertUserCadExistInfo($CUS_DCBLOCO, $CUS_DCAPARTAMENTO, $USU_IDUSUARIO, $CON_IDCONDOMINIO, $CUS_DCFUNCAO)
        {       
            $this->pdoSistema = null; //detruindo objeto para evitar erro de busca do lastid do usuário
            $this->conexaoSistema();    

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

                try {
                    $sql = "INSERT INTO CUS_CONDOUSUARIO 
                            (USU_IDUSUARIO, CON_IDCONDOMINIO, CUS_DCBLOCO, CUS_DCAPARTAMENTO, CUS_DCFUNCAO) 
                            VALUES (:USU_IDUSUARIO, :CON_IDCONDOMINIO, :CUS_DCBLOCO, :CUS_DCAPARTAMENTO, :CUS_DCFUNCAO)";
    
                    $stmt = $this->pdoSistema->prepare($sql);
                
                    // Liga os parâmetros aos valores
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                    $stmt->bindParam(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCBLOCO', $CUS_DCBLOCO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCAPARTAMENTO', $CUS_DCAPARTAMENTO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCFUNCAO', $CUS_DCFUNCAO, PDO::PARAM_STR);
                    $stmt->execute();
    
                    return ["success" => "Morador cadastrado com sucesso."];   
    
                } catch (PDOException $e) {
                    // Captura e retorna o erro
                    return ["error" => $e->getMessage()];
                }        
            
        }

        public function insertFuncionarioCadExistInfo($USU_IDUSUARIO, $CON_IDCONDOMINIO, $CUS_DCFUNCAO)
        {       
            $this->pdoSistema = null; //detruindo objeto para evitar erro de busca do lastid do usuário
            $this->conexaoSistema();    

            $now = new DateTime(); 
            $DATA = $now->format('Y-m-d H:i:s');

                try {
                    $sql = "INSERT INTO CUS_CONDOUSUARIO 
                            (USU_IDUSUARIO, CON_IDCONDOMINIO, CUS_DCFUNCAO) 
                            VALUES (:USU_IDUSUARIO, :CON_IDCONDOMINIO, :CUS_DCFUNCAO)";
    
                    $stmt = $this->pdoSistema->prepare($sql);
                
                    // Liga os parâmetros aos valores
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                    $stmt->bindParam(':CON_IDCONDOMINIO', $this->BANCODEDADOS_CONDOMINIO, PDO::PARAM_STR);
                    $stmt->bindParam(':CUS_DCFUNCAO', $CUS_DCFUNCAO, PDO::PARAM_STR);
                    $stmt->execute();
    
                    return ["success" => "Funcionário cadastrado com sucesso."];   
    
                } catch (PDOException $e) {
                    // Captura e retorna o erro
                    return ["error" => $e->getMessage()];
                }        
            
        }

        public function insertMembroUserInfo($USU_IDUSUARIO, $MOR_DCNOME, $MOR_STPET, $MOR_DTNASCIMENTO, $MOR_DCFOTO)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }  

                try {
                    $sql = "INSERT INTO MOR_MORADORES 
                            (USU_IDUSUARIO, MOR_DCNOME, MOR_STPET, MOR_DTNASCIMENTO, MOR_DCFOTO) 
                            VALUES (:USU_IDUSUARIO, :MOR_DCNOME, :MOR_STPET, :MOR_DTNASCIMENTO, :MOR_DCFOTO)";
    
                    $stmt = $this->pdo->prepare($sql);
                
                    // Liga os parâmetros aos valores
                    $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                    $stmt->bindParam(':MOR_DCNOME', $MOR_DCNOME, PDO::PARAM_STR);
                    $stmt->bindParam(':MOR_STPET', $MOR_STPET, PDO::PARAM_STR);
                    $stmt->bindParam(':MOR_DTNASCIMENTO', $MOR_DTNASCIMENTO, PDO::PARAM_STR);
                    $stmt->bindParam(':MOR_DCFOTO', $MOR_DCFOTO, PDO::PARAM_STR);
                    $stmt->execute();
    
                    $this->insertLogInfo("Insert", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Membro da família cadastrado com sucesso. $MOR_DCNOME");
                    $response = array("success" => true, "message" => "Membro cadastrado com sucesso.");
                    return json_encode($response);
    
                } catch (PDOException $e) {
                    $erro = $e->getMessage();
                    $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Erro ao registrar o membro da família $MOR_DCNOME: $erro");  
                    $response = array("success" => false, "message" => "Erro ao cadastrar membro no banco de dados.");
                    return json_encode($response);
                }        
            
        }

        public function updateMembroUserInfo($MOR_IDMORADORES, $MOR_DCNOME, $MOR_STPET, $MOR_DTNASCIMENTO, $MOR_DCFOTO)
        {       
            if (!$this->pdo) {
                $this->conexao();
            }  

                try {
                    $sql = "UPDATE MOR_MORADORES 
                    SET MOR_DCNOME = :MOR_DCNOME, 
                        MOR_STPET = :MOR_STPET, 
                        MOR_DTNASCIMENTO = :MOR_DTNASCIMENTO, 
                        MOR_DCFOTO = :MOR_DCFOTO
                  WHERE MOR_IDMORADORES = :MOR_IDMORADORES";
    
                    $stmt = $this->pdo->prepare($sql);
                
                    // Liga os parâmetros aos valores
                    $stmt->bindParam(':MOR_IDMORADORES', $MOR_IDMORADORES, PDO::PARAM_STR); 
                    $stmt->bindParam(':MOR_DCNOME', $MOR_DCNOME, PDO::PARAM_STR);
                    $stmt->bindParam(':MOR_STPET', $MOR_STPET, PDO::PARAM_STR);
                    $stmt->bindParam(':MOR_DTNASCIMENTO', $MOR_DTNASCIMENTO, PDO::PARAM_STR);
                    $stmt->bindParam(':MOR_DCFOTO', $MOR_DCFOTO, PDO::PARAM_STR);
                    $stmt->execute();
                    
                    $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"O membro da família $MOR_DCNOME foi atualizado com sucesso.");
                    $response = array("success" => true, "message" => "Membro atualizado com sucesso.");
                    return json_encode($response);
    
                } catch (PDOException $e) {
                    $erro = $e->getMessage();
                    $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A atualização do membro da família $MOR_DCNOME falhou: $erro");
                    $response = array("success" => false, "message" => "Erro ao atualizar o membro no banco de dados.");
                    return json_encode($response);
                }        
            
        }


        public function updateUserInfo($USU_DCEMAIL, $USU_DCNOME, $USU_DCBLOCO, $USU_DCAPARTAMENTO, $USU_DCNIVEL, $USU_DCSENHA, $USU_DCTELEFONE)
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try {

                if($USU_DCSENHA != "IGNORE")
                {
                    $sql = "UPDATE USU_USUARIO 
                            SET
                            USU_DCEMAIL = :USU_DCEMAIL,
                            USU_DCNOME = :USU_DCNOME,
                            USU_DCBLOCO = :USU_DCBLOCO,
                            USU_DCNIVEL = :USU_DCNIVEL,
                            USU_DCSENHA = :USU_DCSENHA,
                            USU_DCTELEFONE = :USU_DCTELEFONE
                            WHERE USU_DCAPARTAMENTO = :USU_DCAPARTAMENTO";

                    $stmt = $this->pdo->prepare($sql);
                    $stmt->bindParam(':USU_DCSENHA', $USU_DCSENHA, PDO::PARAM_STR);
                }
                if($USU_DCSENHA == "IGNORE")
                {
                    $sql = "UPDATE USU_USUARIO 
                            SET
                            USU_DCEMAIL = :USU_DCEMAIL,
                            USU_DCNOME = :USU_DCNOME,
                            USU_DCBLOCO = :USU_DCBLOCO,
                            USU_DCAPARTAMENTO = :USU_DCAPARTAMENTO,
                            USU_DCNIVEL = :USU_DCNIVEL,
                            USU_DCTELEFONE = :USU_DCTELEFONE
                            WHERE USU_DCAPARTAMENTO = :USU_DCAPARTAMENTO";

                    $stmt = $this->pdo->prepare($sql);
                }                
            
                // Liga os parâmetros aos valores
                $stmt->bindParam(':USU_DCEMAIL', $USU_DCEMAIL, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCNOME', $USU_DCNOME, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCBLOCO', $USU_DCBLOCO, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCAPARTAMENTO', $USU_DCAPARTAMENTO, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCNIVEL', $USU_DCNIVEL, PDO::PARAM_STR);
                $stmt->bindParam(':USU_DCTELEFONE', $USU_DCTELEFONE, PDO::PARAM_STR);
            
                $stmt->execute();
           
                // Retorna uma mensagem de sucesso (opcional)
                return ["success" => "Morador atualizado com sucesso."];
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }
        
        public function updateCheckboxEncomendasDisponivelMorador($ENC_IDENCOMENDA, $ENC_STENCOMENDA)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE ENC_ENCOMENDA SET ENC_STENCOMENDA = :ENC_STENCOMENDA WHERE ENC_IDENCOMENDA = :ENC_IDENCOMENDA";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_IDENCOMENDA', $ENC_IDENCOMENDA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_STENCOMENDA', $ENC_STENCOMENDA, PDO::PARAM_STR); 
                $stmt->execute();     

                $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Encomenda com id $ENC_IDENCOMENDA foi alterada seu status para $ENC_STENCOMENDA.");  

                return ["success" => "Encomenda atualizada com sucesso."];

            } catch (PDOException $e) {
                // Captura e retorna o erro
                $erro = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A alteração de status da encomenda com id $ENC_IDENCOMENDA para o status $ENC_STENCOMENDA falhou: ");
                return ["error" => $e->getMessage()];
            }
        }

        public function updateCheckboxFuncoesSistemas($FUS_IDFUNCOES_SISTEMAS, $COLUNA, $VALOR)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            $now = new DateTime(); 
            $FUS_DTLASTUPDATE = $now->format('Y-m-d H:i:s');
        
            // Lista de colunas permitidas para evitar SQL Injection
            $colunasPermitidas = ['FUS_DCVIEW', 'FUS_DCINSERT', 'FUS_DCUPDATE', 'FUS_DCDELETE', 'FUS_DCENABLED', 'FUS_DCIA'];
        
            if (!in_array($COLUNA, $colunasPermitidas)) {
                return ["error" => "Coluna inválida."];
            }
        
            try
            {
                $sql = "UPDATE ADM_FUS_FUNCOES_SISTEMAS SET $COLUNA = :VALOR, FUS_DTLASTUPDATE = :FUS_DTLASTUPDATE WHERE FUS_IDFUNCOES_SISTEMAS = :FUS_IDFUNCOES_SISTEMAS";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VALOR', $VALOR, PDO::PARAM_STR);
                $stmt->bindParam(':FUS_IDFUNCOES_SISTEMAS', $FUS_IDFUNCOES_SISTEMAS, PDO::PARAM_STR);
                $stmt->bindParam(':FUS_DTLASTUPDATE', $FUS_DTLASTUPDATE, PDO::PARAM_STR);
                $stmt->execute();
            
                $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Alterada a permissão da função com id $FUS_IDFUNCOES_SISTEMAS, $COLUNA para $VALOR.");
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao alterar a permissão da função com id $FUS_IDFUNCOES_SISTEMAS, $COLUNA para $VALOR. Erro: $error");
            }
        }

        public function updateCheckboxGuiaRapido($GUR_IDGUIA_RAPIDO, $GUR_STSTATUS)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            $now = new DateTime(); 
            $GUR_DTLASTUPDATE = $now->format('Y-m-d H:i:s');
               
            try
            {
                $sql = "UPDATE GUR_GUIA_RAPIDO SET GUR_STSTATUS = :GUR_STSTATUS, GUR_DTLASTUPDATE = :GUR_DTLASTUPDATE WHERE GUR_IDGUIA_RAPIDO = :GUR_IDGUIA_RAPIDO";
                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':GUR_STSTATUS', $GUR_STSTATUS, PDO::PARAM_STR);
                $stmt->bindParam(':GUR_IDGUIA_RAPIDO', $GUR_IDGUIA_RAPIDO, PDO::PARAM_STR);
                $stmt->bindParam(':GUR_DTLASTUPDATE', $GUR_DTLASTUPDATE, PDO::PARAM_STR);
                $stmt->execute();          
            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, "","Houve um erro ao atualizar o status do guia rápido com ID $GUR_IDGUIA_RAPIDO. Erro: $error");
            }
        }

        public function updateCheckboxPublicidadeParceiro($PDS_IDPRESTADOR_SERVICO, $PDS_STSTATUS)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE PDS_PUBLICIDADE SET PDS_STSTATUS = :PDS_STSTATUS WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_STSTATUS', $PDS_STSTATUS, PDO::PARAM_STR); 
                $stmt->execute();     

                $response = array("success" => true, "message" => "Publicidade preparada para publicação com sucesso.");
                $this->insertLogInfo("info", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Status da publicidade $PDS_IDPRESTADOR_SERVICO foi alterada com sucesso.");
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $response = array("success" => false, "message" => "Houve um erro ao alterar o status da publicidade. - $erro");
                $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Houve um erro ao alterar o status da publicidade. - $erro");
                return json_encode($response); 
            }
        }

        public function updateCheckboxPublicidadeParceiroCron($PDS_IDPRESTADOR_SERVICO, $PDS_STSTATUS)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try
            {         
                $sql = "UPDATE PDS_PUBLICIDADE SET PDS_STSTATUS = :PDS_STSTATUS WHERE PDS_IDPRESTADOR_SERVICO = :PDS_IDPRESTADOR_SERVICO";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PDS_IDPRESTADOR_SERVICO', $PDS_IDPRESTADOR_SERVICO, PDO::PARAM_STR);
                $stmt->bindParam(':PDS_STSTATUS', $PDS_STSTATUS, PDO::PARAM_STR); 
                $stmt->execute();     

                $response = array("success" => true, "message" => "Publicidade preparada para publicação com sucesso.");                
                return json_encode($response); 

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $response = array("success" => false, "message" => "Houve um erro ao alterar o status da publicidade. - $erro");                
                return json_encode($response); 
            }
        }

        public function updateCheckboxEncomendasPortaria($ENC_IDENCOMENDA, $ENC_STENTREGA_MORADOR, $ENC_DCMODO_ENTREGA)
        {
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            if($ENC_STENTREGA_MORADOR == "ENTREGUE")
            {
                $now = new DateTime(); 
                $DATA = $now->format('Y-m-d H:i:s');
            }
            else
                {
                    $DATA = "0000-00-00 00:00:00";
                }


            try
            {         
                $sql = "UPDATE ENC_ENCOMENDA SET ENC_DCMODO_ENTREGA = :ENC_DCMODO_ENTREGA, ENC_STENTREGA_MORADOR = :ENC_STENTREGA_MORADOR, ENC_DTENTREGA_MORADOR = :ENC_DTENTREGA_MORADOR WHERE ENC_IDENCOMENDA = :ENC_IDENCOMENDA";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_IDENCOMENDA', $ENC_IDENCOMENDA, PDO::PARAM_STR);
                $stmt->bindParam(':ENC_STENTREGA_MORADOR', $ENC_STENTREGA_MORADOR, PDO::PARAM_STR); 
                $stmt->bindParam(':ENC_DTENTREGA_MORADOR', $DATA, PDO::PARAM_STR); 
                $stmt->bindParam(':ENC_DCMODO_ENTREGA', $ENC_DCMODO_ENTREGA, PDO::PARAM_STR); 
                $stmt->execute();    
                                               
                $this->insertLogInfo("update", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A encomenda com id $ENC_IDENCOMENDA foi alterada seu status para $ENC_STENTREGA_MORADOR");  

                return ["success" => "Encomenda atualizada com sucesso."];

            } catch (PDOException $e) {
                $erro = $e->getMessage();
                $this->insertLogInfo("error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"A alteração de status para $ENC_STENTREGA_MORADOR da encomenda com id $ENC_IDENCOMENDA falhou: $erro");  
                return ["error" => $e->getMessage()];
            }
        }

        public function updateTerPrivacidade($USU_IDUSUARIO, $NOME, $APARTAMENTO)
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try
            {         
                $sql = "UPDATE USU_USUARIO SET USU_STTERMOPRIVACIDADE = 'ACEITO' WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();    
                
                return ["status" => "sucesso", "mensagem" => "Aceite registrado com sucesso!"];

            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function updateTerUso($USU_IDUSUARIO, $NOME, $APARTAMENTO)
        {
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            try
            {         
                $sql = "UPDATE USU_USUARIO SET USU_STTERMOUSO = 'ACEITO' WHERE USU_IDUSUARIO = :USU_IDUSUARIO";
                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR);
                $stmt->execute();    
                
                return ["status" => "sucesso", "mensagem" => "Aceite registrado com sucesso!"];

            } catch (PDOException $e) {
                // Captura e retorna o erro
                return ["error" => $e->getMessage()];
            }
        }

        public function bdLogClear()
        {       
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }

            try 
            {
                $sql = "DELETE FROM LOG_LOGSISTEMA WHERE LOG_DTLOG < CURDATE() - INTERVAL 90 DAY";

                $stmt = $this->pdo->prepare($sql);           
                $stmt->execute();

                return "Limpeza dos logs realizada com sucesso.";
           
            } catch (PDOException $e) {
                // Captura e retorna o erro
                return "Erro ao executar a limpeza dos Logs: ".$e->getMessage();
            }
        }

        public function getLogInfo($LOG_IDCONDOMINIO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT * FROM LOG_LOGSISTEMA WHERE LOG_IDCONDOMINIO = :LOG_IDCONDOMINIO ORDER BY LOG_DTLOG DESC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':LOG_IDCONDOMINIO', $LOG_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_LOGINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPlanoId($CON_IDCONDOMINIO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT * FROM CON_CONDOMINIO WHERE CON_IDCONDOMINIO = :CON_IDCONDOMINIO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':CON_IDCONDOMINIO', $CON_IDCONDOMINIO, PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getUserByIdEncomenda($ENC_IDENCOMENDA) 
        {          
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {           
                // 1ª busca: pegar bloco e apartamento
                $sql = "SELECT ENC_DCBLOCO, ENC_DCAPARTAMENTO 
                        FROM ENC_ENCOMENDA 
                        WHERE ENC_IDENCOMENDA = :ENC_IDENCOMENDA";
        
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':ENC_IDENCOMENDA', $ENC_IDENCOMENDA, PDO::PARAM_STR);
                $stmt->execute();
                $resultEnc = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if (!$resultEnc) {
                    return ["error" => "Encomenda não encontrada."];
                }
            
                $bloco = $resultEnc['ENC_DCBLOCO'];
                $apartamento = $resultEnc['ENC_DCAPARTAMENTO'];
            
                // 2ª busca: pegar todos os IDs na tabela CUS_CONDOUSUARIO com mesmo bloco e apartamento
                $sql2 = "SELECT USU_IDUSUARIO 
                         FROM CUS_CONDOUSUARIO 
                         WHERE CUS_DCBLOCO = :bloco 
                         AND CUS_DCAPARTAMENTO = :apartamento
                         AND (CUS_DCFUNCAO = 'INQUILINO' OR CUS_DCFUNCAO = 'PROPRIETARIO/MORADOR' OR CUS_DCFUNCAO = 'CONSELHEIRO/MORADOR')";
        
                $stmt2 = $this->pdoSistema->prepare($sql2);
                $stmt2->bindParam(':bloco', $bloco, PDO::PARAM_STR);
                $stmt2->bindParam(':apartamento', $apartamento, PDO::PARAM_STR);
                $stmt2->execute();
                $resultUsers = $stmt2->fetchAll(PDO::FETCH_COLUMN);

                $condominio = $this->BANCODEDADOS_CONDOMINIO;

                return implode(', ', array_map(function($id) use ($condominio) {
                    return '"' . $condominio . '-' . $id . '"';
                }, $resultUsers));

            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao enviar a buscar as informações do morador para notificação push. $error");
            }          
        }

        public function getUserByIdVisitante($VIS_IDVISITANTE) 
        {          
            // Verifica se a conexão já foi estabelecida
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }

            // Verifica se a conexão já foi estabelecida
            if (!$this->pdo) {
                $this->conexao();
            }
        
            try {           
                // 1ª busca: pegar bloco e apartamento
                $sql = "SELECT VIS_DCBLOCO, VIS_DCAPARTAMENTO 
                        FROM VIS_VISITANTE 
                        WHERE VIS_IDVISITANTE = :VIS_IDVISITANTE";
        
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':VIS_IDVISITANTE', $VIS_IDVISITANTE, PDO::PARAM_STR);
                $stmt->execute();
                $resultVis = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if (!$resultVis) {
                    return ["error" => "visitante não encontrado."];
                }
            
                $bloco = $resultVis['VIS_DCBLOCO'];
                $apartamento = $resultVis['VIS_DCAPARTAMENTO'];
            
                // 2ª busca: pegar todos os IDs na tabela CUS_CONDOUSUARIO com mesmo bloco e apartamento
                $sql2 = "SELECT USU_IDUSUARIO 
                         FROM CUS_CONDOUSUARIO 
                         WHERE CUS_DCBLOCO = :bloco 
                         AND CUS_DCAPARTAMENTO = :apartamento
                         AND (CUS_DCFUNCAO = 'INQUILINO' OR CUS_DCFUNCAO = 'PROPRIETARIO/MORADOR' OR CUS_DCFUNCAO = 'CONSELHEIRO/MORADOR')";
        
                $stmt2 = $this->pdoSistema->prepare($sql2);
                $stmt2->bindParam(':bloco', $bloco, PDO::PARAM_STR);
                $stmt2->bindParam(':apartamento', $apartamento, PDO::PARAM_STR);
                $stmt2->execute();
                $resultUsers = $stmt2->fetchAll(PDO::FETCH_COLUMN);

                $condominio = $this->BANCODEDADOS_CONDOMINIO;

                return implode(', ', array_map(function($id) use ($condominio) {
                    return '"' . $condominio . '-' . $id . '"';
                }, $resultUsers));

            
            } catch (PDOException $e) {
                $error = $e->getMessage();
                $this->insertLogInfo("error", "-", "-", $this->BANCODEDADOS_CONDOMINIO,"Houve um erro ao enviar a buscar as informações do morador para notificação push. $error");
            }          
        }


        public function getGuiaRapidoInfo() 
        {          
            // Verifica se a conexão já foi estabelecida
            if(!$this->pdoSistema){$this->conexaoSistema();}
            
            try{           
                $sql = "SELECT * FROM GUR_GUIA_RAPIDO ORDER BY GUR_DTLASTUPDATE DESC";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getVagasDisponiveis() 
        {          
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT VGA_IDVAGAS_VISITANTE, VGA_NMVAGA FROM VGA_VAGAS_VISITANTE WHERE VIS_IDVISITANTE IS NULL ORDER BY VGA_NMVAGA ASC";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $this->ARRAY_VAGASDISPONIVEIS = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getVagasOcupadas() 
        {          
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT VIS_DCPLACA_VEICULO, VIS_DCNOME, VIS_DCBLOCO, VIS_DCAPARTAMENTO, VIS_DTENTRADA, VGA_NMVAGA, VGA_DTENTRADA FROM VW_VISITANTES WHERE VGA_NMVAGA IS NOT NULL";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getArtigosInfoById($INA_IDINSTRUCOES_ADEQUACOES) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM INA_INSTRUCOES_ADEQUACOES WHERE INA_IDINSTRUCOES_ADEQUACOES = :INA_IDINSTRUCOES_ADEQUACOES";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':INA_IDINSTRUCOES_ADEQUACOES', $INA_IDINSTRUCOES_ADEQUACOES, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_ARTIGOSINFO = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getInicialInfo() 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM IFO_INICIAL_INFO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getPendenciaInfoById($EPE_IDEVOLUCAO_PENDENCIA) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM EPE_EVOLUCAO_PENDENCIA WHERE EPE_IDEVOLUCAO_PENDENCIA = :EPE_IDEVOLUCAO_PENDENCIA";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':EPE_IDEVOLUCAO_PENDENCIA', $EPE_IDEVOLUCAO_PENDENCIA, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_PENDENCIAINFO = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function getHashImgInfo($PEM_DCRACA = "", $PET_DCCOR = "", $PEM_DCTIPO = "") 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}

            $PEM_DCRACA = "%".$PEM_DCRACA."%";  // Adiciona o % antes de associar ao bind
            $PET_DCCOR = "%".$PET_DCCOR."%";  // Adiciona o % antes de associar ao bind
            $PEM_DCTIPO = "%".$PEM_DCTIPO."%";  // Adiciona o % antes de associar ao bind
            
            try{           
                $sql = "SELECT * FROM PEM_PETMORADOR WHERE 
                PEM_DCTIPO LIKE :PEM_DCTIPO AND 
                PET_DCCOR LIKE :PET_DCCOR AND PEM_DCRACA LIKE :PEM_DCRACA";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':PEM_DCTIPO', $PEM_DCTIPO, PDO::PARAM_STR);
                $stmt->bindParam(':PET_DCCOR', $PET_DCCOR, PDO::PARAM_STR);
                $stmt->bindParam(':PEM_DCRACA', $PEM_DCRACA, PDO::PARAM_STR);
                $stmt->execute();
                $this->ARRAY_HASHIMGINFO = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function checkTermoPrivacidade($USU_IDUSUARIO) 
        {          
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }
            
            try{           
                $sql = "SELECT USU_STTERMOPRIVACIDADE FROM USU_USUARIO 
                        WHERE USU_IDUSUARIO = :USU_IDUSUARIO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function checkTermoUso($USU_IDUSUARIO) 
        {          
            if (!$this->pdoSistema) {
                $this->conexaoSistema();
            }
            
            try{           
                $sql = "SELECT USU_STTERMOUSO FROM USU_USUARIO 
                        WHERE USU_IDUSUARIO = :USU_IDUSUARIO";

                $stmt = $this->pdoSistema->prepare($sql);
                $stmt->bindParam(':USU_IDUSUARIO', $USU_IDUSUARIO, PDO::PARAM_STR); 
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

        public function checkReportExists($CON_DCMES_COMPETENCIA_USUARIO, $CON_DCANO_COMPETENCIA_USUARIO) 
        {          
                // Verifica se a conexão já foi estabelecida
                if(!$this->pdo){$this->conexao();}
            
            try{           
                $sql = "SELECT * FROM CON_CONCILIACAO 
                        WHERE CON_DCANO_COMPETENCIA_USUARIO = :CON_DCANO_COMPETENCIA_USUARIO
                        AND CON_DCMES_COMPETENCIA_USUARIO = :CON_DCMES_COMPETENCIA_USUARIO";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':CON_DCMES_COMPETENCIA_USUARIO', $CON_DCMES_COMPETENCIA_USUARIO, PDO::PARAM_STR); 
                $stmt->bindParam(':CON_DCANO_COMPETENCIA_USUARIO', $CON_DCANO_COMPETENCIA_USUARIO, PDO::PARAM_STR); 
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ["error" => $e->getMessage()];
            }          
        }

    }
?>
