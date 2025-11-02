<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

class Anamnese extends Conn { 

    /**
     * Retorna o modelo de anamnese ativo para o condomínio especificado.
     *
     * @param int $TENANCY_ID ID da clinica (tenant).
     * @return array|string Retorna os dados do modelo ou JSON com mensagem de erro.
     */
    public function getAnamneseModel($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM ANM_ANAMNESE_MODELO WHERE ANM_STSTATUS = 'ATIVO' AND TENANCY_ID = :TENANCY_ID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Erro ao consultar dados. Tente novamente."
            ]);
        } 
    }

    /**
     * Consulta um paciente com base no código de autenticação da anamnese.
     *
     * @param string $ANR_DCCOD_AUTENTICACAO Código único de autenticação da anamnese.
     * @return array|string Retorna os dados do paciente ou JSON com mensagem de erro.
     */
    public function getAnamneseCheckByCodAuth($ANR_DCCOD_AUTENTICACAO) {
        try{           
            $sql = "SELECT * FROM VW_PACIENTES WHERE ANR_DCCOD_AUTENTICACAO = :ANR_DCCOD_AUTENTICACAO";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":ANR_DCCOD_AUTENTICACAO", $ANR_DCCOD_AUTENTICACAO, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Erro ao consultar dados. Tente novamente."
            ]);
        } 
    }

    /**
     * Retorna a resposta de anamnese associada a um paciente específico.
     *
     * @param int $TENANCY_ID ID da clinica (tenant).
     * @param int $PAC_IDPACIENTE ID do paciente.
     * @return array|string Retorna a resposta encontrada ou JSON com mensagem de erro.
     */
    public function getAnamneseRespostaByPacienteId($TENANCY_ID, $PAC_IDPACIENTE) {
        try{           
            $sql = "SELECT * FROM ANR_ANAMNESE_RESPOSTA WHERE PAC_IDPACIENTE = :PAC_IDPACIENTE AND TENANCY_ID = :TENANCY_ID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID, PDO::PARAM_INT);
            $stmt->bindParam(":PAC_IDPACIENTE", $PAC_IDPACIENTE, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Erro ao consultar dados. Tente novamente."
            ]);
        } 
    }

    /**
     * Insere as respostas de anamnese de um paciente, garantindo que ele não tenha preenchido anteriormente.
     *
     * @param int $TENANCY_ID ID da clinica (tenant).
     * @param int $PAC_IDPACIENTE ID do paciente.
     * @param int $ANM_IDANAMNESE_MODELO ID do modelo de anamnese utilizado.
     * @param array $ANR_JSON_RESPOSTAS Respostas preenchidas pelo paciente.
     * @return string JSON informando sucesso ou erro na inserção.
     */
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
        $ANR_DCIP_NAVEGADOR = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        $ANR_DCUSER_AGENT = $_SERVER['HTTP_USER_AGENT'];

        try {
            $sql = "INSERT INTO ANR_ANAMNESE_RESPOSTA (TENANCY_ID, ANR_DCUSER_AGENT, ANR_DCIP_NAVEGADOR, ANR_DCCOD_AUTENTICACAO, PAC_IDPACIENTE, ANM_IDANAMNESE_MODELO, ANR_JSON_RESPOSTAS, ANR_DTCREATE_AT) 
                    VALUES (:TENANCY_ID, :ANR_DCUSER_AGENT, :ANR_DCIP_NAVEGADOR, :ANR_DCCOD_AUTENTICACAO, :PAC_IDPACIENTE, :ANM_IDANAMNESE_MODELO, :ANR_JSON_RESPOSTAS, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID, PDO::PARAM_INT);
            $stmt->bindParam(":PAC_IDPACIENTE", $PAC_IDPACIENTE, PDO::PARAM_INT);
            $stmt->bindParam(":ANM_IDANAMNESE_MODELO", $ANM_IDANAMNESE_MODELO, PDO::PARAM_INT); 
            $stmt->bindParam(":ANR_JSON_RESPOSTAS", $ANR_JSON_RESPOSTAS, PDO::PARAM_STR); 
            $stmt->bindParam(":ANR_DCCOD_AUTENTICACAO", $ANR_DCCOD_AUTENTICACAO, PDO::PARAM_STR); 
            $stmt->bindParam(":ANR_DCIP_NAVEGADOR", $ANR_DCIP_NAVEGADOR, PDO::PARAM_STR); 
            $stmt->bindParam(":ANR_DCUSER_AGENT", $ANR_DCUSER_AGENT, PDO::PARAM_STR); 
        
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

    /**
     * Retorna o modelo de anamnese e suas respostas associadas a um paciente específico.
     *
     * @param int $TENANCY_ID ID da clinica (tenant).
     * @param int $PAC_IDPACIENTE ID do paciente.
     * @return array|string Retorna dados combinados do modelo e das respostas ou JSON com erro.
     */
    public function getAnamnesesRespostaModeloByIdPaciente($TENANCY_ID, $PAC_IDPACIENTE) {
        try{  
            $sql = " SELECT r.ANR_IDANAMNESE_RESPOSTA, r.ANR_JSON_RESPOSTAS, m.ANM_JSON_MODELO, r.ANR_DCCOD_AUTENTICACAO
            FROM ANR_ANAMNESE_RESPOSTA r
            INNER JOIN ANM_ANAMNESE_MODELO m ON (m.ANM_IDANAMNESE_MODELO = r.ANM_IDANAMNESE_MODELO)
            WHERE r.PAC_IDPACIENTE = :PAC_IDPACIENTE AND r.TENANCY_ID = :TENANCY_ID";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID, PDO::PARAM_INT);
            $stmt->bindParam(":PAC_IDPACIENTE", $PAC_IDPACIENTE, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Erro ao consultar dados. Tente novamente."
            ]);
        } 
    }
}
