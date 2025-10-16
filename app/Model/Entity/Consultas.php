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
            $dataInicio = $dataFim = null;

            switch ($diaSemana) {
                case "1": // Hoje
                    $dataInicio = $dataFim = date('Y-m-d');
                    break;
                case "2": // Últimos 6 meses
                    $dataInicio = date('Y-m-d', strtotime('-6 months'));
                    $dataFim = date('Y-m-d');
                    break;
                case "3": // Próximos 6 meses
                    $dataInicio = date('Y-m-d');
                    $dataFim = date('Y-m-d', strtotime('+6 months'));
                    break;
                case "4": // Últimos 2 anos
                    $dataInicio = date('Y-m-d', strtotime('-2 years'));
                    $dataFim = date('Y-m-d');
                    break;
                default:
                    throw new Exception("Opção inválida para diaSemana.");
            }

            $sql = "SELECT * 
                    FROM VW_CONSULTAS 
                    WHERE TENANCY_ID = :TENANCY_ID
                      AND DEN_IDDENTISTA = :DEN_IDDENTISTA
                      AND CON_DTCONSULTA BETWEEN :dataInicio AND :dataFim
                    ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":DEN_IDDENTISTA", $DEN_IDDENTISTA);
            $stmt->bindParam(":dataInicio", $dataInicio);
            $stmt->bindParam(":dataFim", $dataFim);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


    public function getConsultasByDayPredef($TENANCY_ID, $diaSemana) {
        try {     
            $dataInicio = $dataFim = null;

            switch ($diaSemana) {
                case "1": // Hoje
                    $dataInicio = $dataFim = date('Y-m-d');
                    break;
                case "2": // Últimos 6 meses
                    $dataInicio = date('Y-m-d', strtotime('-6 months'));
                    $dataFim = date('Y-m-d');
                    break;
                case "3": // Próximos 6 meses
                    $dataInicio = date('Y-m-d');
                    $dataFim = date('Y-m-d', strtotime('+6 months'));
                    break;
                case "4": // Últimos 2 anos
                    $dataInicio = date('Y-m-d', strtotime('-2 years'));
                    $dataFim = date('Y-m-d');
                    break;
                default:
                    throw new Exception("Opção inválida para diaSemana.");
            }

            $sql = "SELECT * 
                    FROM VW_CONSULTAS 
                    WHERE TENANCY_ID = :TENANCY_ID
                      AND CON_DTCONSULTA BETWEEN :dataInicio AND :dataFim
                    ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":dataInicio", $dataInicio);
            $stmt->bindParam(":dataFim", $dataFim);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }


    public function getHorariosDisponiveis($CON_DTCONSULTA, $CON_NUMDURACAO, $TENANCY_ID) {
        try {
            $sql = "WITH RECURSIVE horarios_possiveis AS (
                        SELECT TIME('08:00:00') AS horario
                        UNION ALL
                        SELECT ADDTIME(horario, SEC_TO_TIME(:CON_NUMDURACAO*60))
                        FROM horarios_possiveis
                        WHERE ADDTIME(horario, SEC_TO_TIME(:CON_NUMDURACAO*60)) <= TIME('18:00:00')
                    )
                    SELECT h.horario
                    FROM horarios_possiveis h
                    WHERE NOT EXISTS (
                        SELECT 1
                        FROM CON_CONSULTAS c
                        WHERE c.CON_DTCONSULTA = :CON_DTCONSULTA
                          AND c.TENANCY_ID = :TENANCY_ID
                          AND c.CON_ENSTATUS IN ('AGENDADA','CONFIRMADA')
                          AND (
                              h.horario < ADDTIME(c.CON_HORACONSULTA, SEC_TO_TIME(c.CON_NUMDURACAO*60))
                              AND ADDTIME(h.horario, SEC_TO_TIME(:CON_NUMDURACAO*60)) > c.CON_HORACONSULTA
                          )
                    )
                    ORDER BY h.horario";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":CON_DTCONSULTA", $CON_DTCONSULTA);
            $stmt->bindParam(":CON_NUMDURACAO", $CON_NUMDURACAO);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getConsultasToCalendar($TENANCY_ID, $DEN_IDDENTISTA) {
        try {     
            // Intervalo de 12 meses centrado em hoje
            $dataInicio = date('Y-m-d', strtotime('-6 months'));
            $dataFim = date('Y-m-d', strtotime('+6 months'));

            // Monta SQL base
            $sql = "SELECT * 
                    FROM VW_CONSULTAS 
                    WHERE TENANCY_ID = :TENANCY_ID
                        AND CON_DTCONSULTA BETWEEN :dataInicio AND :dataFim";

            // Se não for "all", adiciona filtro do dentista
            if ($DEN_IDDENTISTA !== "all") {
                $sql .= " AND DEN_IDDENTISTA = :DEN_IDDENTISTA";
            }

            $sql .= " ORDER BY CON_DTCONSULTA, CON_HORACONSULTA ASC";

            $stmt = $this->pdo->prepare($sql);

            // Sempre vincula TENANCY e datas
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->bindParam(":dataInicio", $dataInicio);
            $stmt->bindParam(":dataFim", $dataFim);

            // Só vincula dentista se não for "all"
            if ($DEN_IDDENTISTA !== "all") {
                $stmt->bindParam(":DEN_IDDENTISTA", $DEN_IDDENTISTA);
            }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }



}