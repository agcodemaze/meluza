<?php 

namespace App\Model\Entity;

class Organization {

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

}