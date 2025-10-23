<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

/**
 * Classe responsável por gerenciar os eventos
 */
class Eventos extends Conn { 


    public function insertEvento($EVE_DCTIPO, $EVE_ID, $EVE_DCVALOR) {
        try {   
            $EVE_DTEVENTO = date('Y-m-d H:i:s');

            $sql = "INSERT INTO EVE_EVENTOS (EVE_DCTIPO, EVE_ID, EVE_DCVALOR, EVE_DTEVENTO) 
                    VALUES (:EVE_DCTIPO, :EVE_ID, :EVE_DCVALOR, :EVE_DTEVENTO)";
            
            $stmt = $this->pdo->prepare($sql);
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

    public function getEventsStream($ultimoId = 0)
    {
        try {
            $sql = "SELECT * FROM EVE_EVENTOS 
                WHERE EVE_IDEVENTOS > ? AND EVE_DCTIPO = 'CONSULTA' 
                ORDER BY EVE_IDEVENTOS ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$ultimoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [["error" => $e->getMessage()]];
        }
    }
}