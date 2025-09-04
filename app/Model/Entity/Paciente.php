<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

/**
 * Criada a herança da classe Conn para 
 * fazer a conexão com o Bando de dados
 */
class Paciente extends Conn { 

    /**
     * Retorna todos os convênios cadastrados
     * @return array
     */
    public function getConvenios() {
        try{           
            $sql = "SELECT * FROM CNV_CONVENIO ORDER BY CNV_DCCONVENIO ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

}