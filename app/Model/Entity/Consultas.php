<?php 

namespace App\Model\Entity;
use \App\Model\Entity\Conn;
use PDO;
use PDOException;

/**
 * Classe responsável por gerenciar as operações de consultas no banco de dados.
 */
class Consultas extends Conn { 

    /**
     * Busca todas as consultas agendadas para o dia atual com base no fuso horário de São Paulo (-03:00).
     *
     * @param int $TENANCY_ID O ID do inquilino (clínica).
     * @return array Retorna um array associativo com as consultas ou um array com a mensagem de erro.
     */
    public function getConsultasHoje($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM VW_CONSULTAS WHERE CON_DTCONSULTA = DATE(CONVERT_TZ(NOW(), '+00:00', '-03:00')) AND TENANCY_ID = :TENANCY_ID ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

    public function getConsultasByProfissional($TENANCY_ID, $DEN_IDDENTISTA) {
        try{           
            $sql = "SELECT * FROM VW_CONSULTAS WHERE TENANCY_ID = :TENANCY_ID 
            AND DEN_IDDENTISTA = :DEN_IDDENTISTA ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":DEN_IDDENTISTA", $DEN_IDDENTISTA);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

    public function getConsultasByDayProfPredef($TENANCY_ID, $DEN_IDDENTISTA, $diaSemana) {
        try {     
            $dataParametro = null;

            if($diaSemana == "1") {
                $dataParametro = date('Y-m-d'); 
            } 
            elseif($diaSemana == "2") {
                $dataParametro = date('Y-m-d', strtotime('+1 day'));
            }
            elseif($diaSemana == "3") {
                $dataParametro = date('Y-m-d', strtotime('-1 day'));
            } 
            // se $diaSemana não for nenhum dos acima, $dataParametro fica null e trará todos os registros

            $sql = "SELECT * 
                    FROM VW_CONSULTAS 
                    WHERE (:dataConsulta IS NULL OR CON_DTCONSULTA = :dataConsulta)
                    AND TENANCY_ID = :TENANCY_ID
                    AND DEN_IDDENTISTA = :DEN_IDDENTISTA
                    ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":DEN_IDDENTISTA", $DEN_IDDENTISTA);
            $stmt->bindParam(":dataConsulta", $dataParametro);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

    public function getConsultasByDayPredef($TENANCY_ID, $diaSemana) {
        try {     
            $dataParametro = null;

            if($diaSemana == "1") {
                $dataParametro = date('Y-m-d'); 
            } 
            elseif($diaSemana == "2") {
                $dataParametro = date('Y-m-d', strtotime('+1 day'));
            }
            elseif($diaSemana == "3") {
                $dataParametro = date('Y-m-d', strtotime('-1 day'));
            } 
            // se $diaSemana não for nenhum dos acima, $dataParametro fica null e trará todos os registros

            $sql = "SELECT * 
                    FROM VW_CONSULTAS 
                    WHERE (:dataConsulta IS NULL OR CON_DTCONSULTA = :dataConsulta)
                    AND TENANCY_ID = :TENANCY_ID
                    ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":dataConsulta", $dataParametro);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }
}