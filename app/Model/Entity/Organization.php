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
    public $title = 'Site do mics';

    /**
     * Site da Organização
     * @var string
     */
    public $site = "https://merluza.com.br";

    /**
     * Descricao da Organização
     * @var string
     */
    public $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad 
    minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea 
    commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit 
    esse cillum dolore eu fugiat nulla pariatur.";

}