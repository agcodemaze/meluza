<?php
require BASE_PATH . "src/auth.php";
include_once BASE_PATH . "objects/objects.php";

class deleteCliente extends SITE_ADMIN
{
    public function deleteClienteInfo($id, $nome)
    {
        try {
                if (!$this->pdo) {
                    $this->conexao();
                }
               
                $result = $this->deleteClienteById($id, $nome);
                echo $result;
                
        } catch (PDOException $e) {  
            $response = array("success" => false, "message" => "Erro ao excluir o cliente $nome.");
            echo json_encode($response); 
        } 
    }
}

// Processa a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];

     $deleteCliente = new deleteCliente();
     $deleteCliente->deleteClienteInfo($id,$nome);
 }
 ?>