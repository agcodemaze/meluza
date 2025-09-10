<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

class Consultas extends Conn { 

    public function getConsultasHoje($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM VW_CONSULTAS WHERE CON_DTCONSULTA = CURDATE() AND TENANCY_ID = :TENANCY_ID ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }
}