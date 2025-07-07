<?php
require BASE_PATH . "src/auth.php";
include_once BASE_PATH . "objects/objects.php";    
header('Content-Type: application/json');

class registerCliente extends SITE_ADMIN
{
    public function insertCliente($nome, $observacao, $telefone, $cep, $endereco, $numero, $bairro, $cidade, $estado, $complemento)
    {
        try {

            if (!$this->pdo) {
                $this->conexao();
            }
            $IDUSER = USER_ID;

            $sql = "SELECT COUNT(*) as total FROM CLI_CLIENTE 
                    WHERE 
                    CLI_DCNOME = :CLI_DCNOME AND USU_IDUSUARIO = :USU_IDUSUARIO";
        
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':CLI_DCNOME', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':USU_IDUSUARIO', $IDUSER, PDO::PARAM_STR);
            $stmt->execute();
        
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($resultado['total'] > 0) {
                $response = array("success" => false, "message" => "Já existe um cliente com este nome.");
                echo json_encode($response);   
                exit;
            }        
            
            $result = $this->insertClienteInfo($nome, $observacao, $telefone, $cep, $endereco, $numero, $bairro, $cidade, $estado, $complemento, USER_ID);
            echo $result;                   

        } catch (PDOException $e) {  
            $response = array("success" => true, "message" => "Erro ao cadastrar o(a) cliente.");
            echo json_encode($response); 
        } 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = mb_strtoupper(trim($_POST['nome']), 'UTF-8');    
    $observacao = !empty($_POST['observacao']) ? trim($_POST['observacao'], 'UTF-8') : null;
    $telefone = mb_strtoupper(trim($_POST['telefone']), 'UTF-8'); 
    $cep = !empty($_POST['cep']) ? mb_strtoupper(trim($_POST['cep']), 'UTF-8') : null;
    $endereco = !empty($_POST['endereco']) ? mb_strtoupper(trim($_POST['endereco']), 'UTF-8') : null;
    $numero = !empty($_POST['numero']) ? mb_strtoupper(trim($_POST['numero']), 'UTF-8') : null;
    $complemento = !empty($_POST['complemento']) ? mb_strtoupper(trim($_POST['complemento']), 'UTF-8') : null;
    $bairro = !empty($_POST['bairro']) ? mb_strtoupper(trim($_POST['bairro']), 'UTF-8') : null;
    $cidade = !empty($_POST['cidade']) ? mb_strtoupper(trim($_POST['cidade']), 'UTF-8') : null;
    $estado = !empty($_POST['estado']) ? mb_strtoupper(trim($_POST['estado']), 'UTF-8') : null;

    if(empty($nome))
    {
        $response = array("success" => false, "message" => "O nome do cliente precisa ser informado.");
        echo json_encode($response);   
        exit;  
    }

    $registerCliente = new registerCliente();
    $registerCliente->insertCliente($nome, $observacao, $telefone, $cep, $endereco, $numero, $bairro, $cidade, $estado, $complemento);
}
 ?>