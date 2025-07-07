<?php
require BASE_PATH . "src/auth.php";
include_once BASE_PATH . "objects/objects.php";

class deleteFaxina extends SITE_ADMIN
{
    public function deleteFaxinaInfo($id)
    {
        try {
                if (!$this->pdo) {
                    $this->conexao();
                }
               
                $result = $this->deleteFaxinaById($id);
                echo $result;
                
        } catch (PDOException $e) {  
            $response = array("success" => false, "message" => "Erro ao excluir a faxina");
            echo json_encode($response); 
        } 
    }
}

// Processa a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

     $deleteFaxina = new deleteFaxina();
     $deleteFaxina->deleteFaxinaInfo($id);
 }
 ?>