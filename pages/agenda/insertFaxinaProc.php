<?php
require BASE_PATH . "src/auth.php";
include_once BASE_PATH . "objects/objects.php";    
header('Content-Type: application/json');

class registerFaxina extends SITE_ADMIN
{
    public function insertFaxina($id, $cliente, $tipo, $duracao, $preco, $data, $observacao)
    {
        try {

            if (!$this->pdo) {
                $this->conexao();
            }

            if($id == "Cadastrar")
            {
                $result = $this->inserFaxinaInfo($cliente, $tipo, $duracao, $preco, $data, $observacao);
                echo $result;
            }
            else
            {
                $result = $this->editFaxinaInfo($id, $cliente, $tipo, $duracao, $preco, $data, $observacao);
                echo $result;
            }
            
                   

        } catch (PDOException $e) {  
            $response = array("success" => true, "message" => "Erro ao processar a faxina.");
            echo json_encode($response); 
        } 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = isset($_POST['cliente']) && !empty($_POST['cliente']) ? $_POST['cliente'] : 'Cadastrar';   
    $cliente = mb_strtoupper(trim($_POST['cliente']), 'UTF-8');    
    $tipo = mb_strtoupper(trim($_POST['tipo']), 'UTF-8');  
    $duracao = mb_strtoupper(trim($_POST['duracao']), 'UTF-8');  
    $preco = mb_strtoupper(trim($_POST['preco']), 'UTF-8');  

    $data = mb_strtoupper(trim($_POST['dataHora']), 'UTF-8');  
    $observacao = mb_strtoupper(trim($_POST['observacao']), 'UTF-8');  

    $registerFaxina = new registerFaxina();
    $registerFaxina->insertFaxina($id, $cliente, $tipo, $duracao, $preco, $data, $observacao);
}
 ?>