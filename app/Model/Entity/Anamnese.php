<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;


class Anamnese extends Conn { 


    public function getAnamneseModel($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM ANM_ANAMNESE_MODELO WHERE ANM_STSTATUS = 'ATIVO' AND TENANCY_ID = :TENANCY_ID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

    public function getAnamneseRespostaByPacienteId($TENANCY_ID, $PAC_IDPACIENTE) {
        try{           
            $sql = "SELECT * FROM ANR_ANAMNESE_RESPOSTA WHERE PAC_IDPACIENTE = :PAC_IDPACIENTE AND TENANCY_ID = :TENANCY_ID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":PAC_IDPACIENTE", $PAC_IDPACIENTE);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

    public function insertAnamneseRespostas($TENANCY_ID, $PAC_IDPACIENTE, $ANM_IDANAMNESE_MODELO, $ANR_JSON_RESPOSTAS) {
        
        $jaExisteAnanmese = $this->getAnamneseRespostaByPacienteId($TENANCY_ID, $PAC_IDPACIENTE);
        if(!empty($jaExisteAnanmese["ANR_IDANAMNESE_RESPOSTA"])) {
            return json_encode([
                "success" => false,
                "message" => "Já existe uma anamnese preecnhida pelo paciente"
            ]);
        }

        $bloco1 = strtoupper(bin2hex(random_bytes(2))); 
        $bloco2 = strtoupper(bin2hex(random_bytes(2)));
        $bloco3 = strtoupper(bin2hex(random_bytes(2)));            
        $ANR_DCCOD_AUTENTICACAO = $bloco1 . '-' . $bloco2 . '-' . $bloco3;

        $ANR_JSON_RESPOSTAS = json_encode($ANR_JSON_RESPOSTAS, JSON_UNESCAPED_UNICODE);
        $ANR_DCIP_NAVEGADOR = $_SERVER['REMOTE_ADDR'];
        $ANR_DCUSER_AGENT = $_SERVER['HTTP_USER_AGENT'];

        try {
            $sql = "INSERT INTO ANR_ANAMNESE_RESPOSTA (TENANCY_ID, ANR_DCUSER_AGENT, ANR_DCIP_NAVEGADOR, ANR_DCCOD_AUTENTICACAO, PAC_IDPACIENTE, ANM_IDANAMNESE_MODELO, ANR_JSON_RESPOSTAS, ANR_DTCREATE_AT) 
                    VALUES (:TENANCY_ID, :ANR_DCUSER_AGENT, :ANR_DCIP_NAVEGADOR, :ANR_DCCOD_AUTENTICACAO, :PAC_IDPACIENTE, :ANM_IDANAMNESE_MODELO, :ANR_JSON_RESPOSTAS, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":PAC_IDPACIENTE", $PAC_IDPACIENTE);
            $stmt->bindParam(":ANM_IDANAMNESE_MODELO", $ANM_IDANAMNESE_MODELO); 
            $stmt->bindParam(":ANR_JSON_RESPOSTAS", $ANR_JSON_RESPOSTAS); 
            $stmt->bindParam(":ANR_DCCOD_AUTENTICACAO", $ANR_DCCOD_AUTENTICACAO); 
            $stmt->bindParam(":ANR_DCIP_NAVEGADOR", $ANR_DCIP_NAVEGADOR); 
            $stmt->bindParam(":ANR_DCUSER_AGENT", $ANR_DCUSER_AGENT); 
        
            $stmt->execute();
        
            return json_encode([
                "success" => true,
                "message" => "Respostas enviadas com sucesso."
            ]);
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        } 
    }

    public function getAnamnesesRespostaModeloByIdPaciente($TENANCY_ID, $PAC_IDPACIENTE) {
        try{  

            $sql = " SELECT r.ANR_IDANAMNESE_RESPOSTA, r.ANR_JSON_RESPOSTAS, m.ANM_JSON_MODELO, r.ANR_DCCOD_AUTENTICACAO
            FROM ANR_ANAMNESE_RESPOSTA r
            INNER JOIN ANM_ANAMNESE_MODELO m ON (m.ANM_IDANAMNESE_MODELO = r.ANM_IDANAMNESE_MODELO)
            WHERE r.PAC_IDPACIENTE = :PAC_IDPACIENTE AND r.TENANCY_ID = :TENANCY_ID";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":PAC_IDPACIENTE", $PAC_IDPACIENTE);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }
}