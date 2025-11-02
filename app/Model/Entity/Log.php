<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

class Log extends Conn { 

    /**
     * Insere um registro de log no sistema
     * 
     * @param string $LOS_DCUSUARIO Nome ou ID do usuário
     * @param string $LOS_DCNIVEL Nível do log 'CRITICAL','INFO','WARNING','ERROR','NOTICE','DEBUG'
     * @param string $LOS_DCMSG Mensagem do log
     * @param int $TENANCY_ID ID do tenancy (multi-tenant)
     * @return string JSON com sucesso ou erro
     */
    public function insertLog($LOS_DCUSUARIO, $LOS_DCNIVEL, $LOS_DCMSG, $TENANCY_ID) {

        $levels = ['CRITICAL','INFO','WARNING','ERROR','NOTICE','DEBUG'];
        if (!in_array($LOS_DCNIVEL, $levels)) {
            $LOS_DCNIVEL = 'NOTICE'; 
        }

        try {
            $sql = "INSERT INTO LOS_LOGSISTEMA (LOS_DCUSUARIO, LOS_DCNIVEL, LOS_DTCREATE_AT, LOS_DCMSG, TENANCY_ID) 
                    VALUES (:LOS_DCUSUARIO, :LOS_DCNIVEL, NOW(), :LOS_DCMSG, :TENANCY_ID)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":LOS_DCUSUARIO", $LOS_DCUSUARIO, PDO::PARAM_STR);
            $stmt->bindParam(":LOS_DCNIVEL", $LOS_DCNIVEL, PDO::PARAM_STR);  
            $stmt->bindParam(":LOS_DCMSG", $LOS_DCMSG, PDO::PARAM_STR); 
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID, PDO::PARAM_INT); 
        
            $stmt->execute();
        
            return json_encode([
                "success" => true,
                "message" => "Log gravado com sucesso."
            ]);
        } catch (PDOException $e) {
            return json_encode([
                "success" => false,
                "message" => "Falha ao gravar o log."
            ]);
        } 
    }
}
