<?php
include_once BASE_PATH . "objects/objects.php";   
header('Content-Type: application/json');

class registerUser extends SITE_ADMIN
{
    public function insertUser($email, $senha, $nome)
    {
        try {
            // Cria conexão com o banco de dados
            if (!$this->pdo) {
                $this->conexao();
            }

            $nome = strtoupper($nome);
            $email = strtoupper($email);

            $sql = "SELECT COUNT(*) as total FROM USU_USUARIO 
                    WHERE 
                    USU_DCEMAIL = :USU_DCEMAIL";
        
            $stmt = $this->pdoSistema->prepare($sql);
            $stmt->bindParam(':USU_DCEMAIL', $email, PDO::PARAM_STR);
            $stmt->execute();
            
        
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado['total'] > 0) {
                $response = array("success" => false, "message" => "Usuário já está cadastrado.");
                echo json_encode($response);   
                exit;
            } 
        
            $passHash = password_hash($senha, PASSWORD_DEFAULT);
            $result = $this->insertUserInfo($email, $nome, $passHash);
            echo $result;                   
               
        } catch (PDOException $e) {  
            $erro = $e->getMessage();
            $this->insertLogInfo("Error", USER_EMAIL, USER_NIVEL, IDCONDOMINIO,"Erro ao cadastrar o usuário $email: $erro"); 
            $response = array("success" => false, "message" => "Erro ao cadastrar usuário.");
            echo json_encode($response); 
        } 
    }
}

// Processa a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email_user'];
    $senha = $_POST['senha_user'];
    $nome = $_POST['nome'];
  
    $registerUser = new registerUser();
    $registerUser->insertUser($email, $senha, $nome);
 }
 ?>