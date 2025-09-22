<?php 

namespace App\Model\Entity;

use \App\Model\Entity\Conn;
use PDO;
use PDOException;

class Organization extends Conn {

    /**
     * Id da Organização
     * @var integer
     */
    public $id = 1;

    /**
     * Nome da Organização
     * @var string
     */
    public $title = 'SmileCopilot';

       /**
     * Nome da Empresa
     * @var string
     */
    public $nameCompany = 'Dentista Manuel';

    /**
     * Site da Organização
     * @var string
     */
    public $site = "https://merluza.com.br";

    /**
     * Descricao da Organização
     * @var string
     */
    public $description = "Sistema odontológico completo com agenda online, integração ao WhatsApp, anamnese inteligente e diversos recursos. Tenha o melhor suporte do mercado e faça parte da comunidade de mais de 2.000 dentistas";

        /**
     * Descricao da Organização
     * @var string
     */
    public $keywords = "software odontológico, sistema para dentistas, agenda online para consultório, gestão de clínicas odontológicas, prontuário eletrônico odontológico";

        public function getConfiguracoes($TENANCY_ID) {
        try{           
            $sql = "SELECT * FROM CFG_CONFIGURACOES WHERE TENANCY_ID = :TENANCY_ID";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":TENANCY_ID", $TENANCY_ID);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        } 
    }

}