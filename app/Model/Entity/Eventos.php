<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

/**
 * Classe responsável por gerenciar os eventos
 */
class Eventos extends Conn { 


    public function insertEvento($TENANCY_ID, $EVE_DCTIPO, $EVE_ID, $EVE_DCVALOR) {
        try {   
            $EVE_DTEVENTO = date('Y-m-d H:i:s');

            $sql = "INSERT INTO EVE_EVENTOS (TENANCY_ID, EVE_DCTIPO, EVE_ID, EVE_DCVALOR, EVE_DTEVENTO) 
                    VALUES (:TENANCY_ID, :EVE_DCTIPO, :EVE_ID, :EVE_DCVALOR, :EVE_DTEVENTO)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":EVE_DCTIPO", $EVE_DCTIPO);
            $stmt->bindParam(":EVE_ID", $EVE_ID);
            $stmt->bindParam(":EVE_DCVALOR", $EVE_DCVALOR);
            $stmt->bindParam(":EVE_DTEVENTO", $EVE_DTEVENTO);
            $stmt->execute();

            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }
}