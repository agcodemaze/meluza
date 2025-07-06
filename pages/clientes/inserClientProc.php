<?php
require BASE_PATH . "src/auth.php";
include_once BASE_PATH . "objects/objects.php";   
include_once BASE_PATH . "objects/objects_s3.php";  
header('Content-Type: application/json');

class registerPrestador extends SITE_ADMIN
{
    public function insertPrestador($nome, $nomeContato, $descricao, $cpf, $cnpj, $telefone, $categoria, $email, $cep, $endereco, $numero, $bairro, $cidade, $estado, $status, $fotoNome)
    {
        $this->BANCODEDADOS_CONDOMINIO = IDCONDOMINIO;

        try {

            if (!$this->pdo) {
                $this->conexao();
            }

            $sql = "SELECT COUNT(*) as total FROM ADM_FOR_FORNECEDOR 
                    WHERE 
                    FOR_DCNOME = :FOR_DCNOME";
        
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':FOR_DCNOME', $nome, PDO::PARAM_STR);
            $stmt->execute();
        
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($resultado['total'] > 0) {
                $response = array("success" => false, "message" => "Já existe um prestador de serviço cadastrado com este nome.");
                echo json_encode($response);   
                exit;
            }        
            
            $result = $this->insertPrestadorInfo($nome, $telefone, $categoria, $status, $nomeContato, $descricao, $cpf, $cnpj, $email, $cep, $endereco, $numero, $bairro, $cidade, $estado, $fotoNome);

            echo $result;                   

        } catch (PDOException $e) {  
            $response = array("success" => true, "message" => "Erro ao cadastrar usuário.");
            echo json_encode($response); 
        } 
    }
}

// Processa a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dadosS3 = new S3STORAGE(); 
    $dadosS3->BANCODEDADOS_CONDOMINIO = (string) IDCONDOMINIO; 

    $nome = mb_strtoupper(trim($_POST['nome']), 'UTF-8'); // Obrigatório
    $nomeContato = !empty($_POST['nomeContato']) ? mb_strtoupper(trim($_POST['nomeContato']), 'UTF-8') : null;
    $descricao = !empty($_POST['descricao']) ? trim($_POST['descricao'], 'UTF-8') : null;
    $cpf = !empty($_POST['cpf']) ? mb_strtoupper(trim($_POST['cpf']), 'UTF-8') : null;
    $cnpj = !empty($_POST['cnpj']) ? mb_strtoupper(trim($_POST['cnpj']), 'UTF-8') : null;
    $telefone = mb_strtoupper(trim($_POST['telefone']), 'UTF-8'); // Obrigatório
    $categoria = mb_strtoupper(trim($_POST['categoria']), 'UTF-8'); // Obrigatório
    $email = !empty($_POST['email']) ? mb_strtoupper(trim($_POST['email']), 'UTF-8') : null;
    $cep = !empty($_POST['cep']) ? mb_strtoupper(trim($_POST['cep']), 'UTF-8') : null;
    $endereco = !empty($_POST['endereco']) ? mb_strtoupper(trim($_POST['endereco']), 'UTF-8') : null;
    $numero = !empty($_POST['numero']) ? mb_strtoupper(trim($_POST['numero']), 'UTF-8') : null;
    $bairro = !empty($_POST['bairro']) ? mb_strtoupper(trim($_POST['bairro']), 'UTF-8') : null;
    $cidade = !empty($_POST['cidade']) ? mb_strtoupper(trim($_POST['cidade']), 'UTF-8') : null;
    $estado = !empty($_POST['estado']) ? mb_strtoupper(trim($_POST['estado']), 'UTF-8') : null;
    $status = mb_strtoupper(trim($_POST['status']), 'UTF-8'); // Obrigatório
    $fotoNome = "";

    $registerPrestador = new registerPrestador();
    $registerPrestador->insertPrestador($nome, $nomeContato, $descricao, $cpf, $cnpj, $telefone, $categoria, $email, $cep, $endereco, $numero, $bairro, $cidade, $estado, $status, $fotoNome);
}
 ?>