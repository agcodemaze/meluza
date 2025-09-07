<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

/**
 * Criada a herança da classe Conn para 
 * fazer a conexão com o Bando de dados
 */
class Profissionais extends Conn { 

    /**
     * Retorna todos os convênios cadastrados
     * @return array
     */
    public function getProfissionais($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM DEN_DENTISTAS WHERE TENANCY_ID = $TENANCY_ID ORDER BY DEN_DCNOME ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }
}