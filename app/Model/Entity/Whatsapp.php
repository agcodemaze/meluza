<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;


class Whatsapp extends Conn { 

    public function getModelosMsgWhatsapp($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM WMS_WHATSAPPMESSAGE WHERE TENANCY_ID = :TENANCY_ID ORDER BY WMS_DCTITULO ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

}