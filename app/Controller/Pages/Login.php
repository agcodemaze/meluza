<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Login extends PageLogin{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getLogin() {

        $objOrganization = new Organization();
        
        /*
        //debug --------
        echo "<pre>";   
        print_r($convenios);
        echo "<pre>"; 
        exit;
        //debug --------
        */

        //VIEW DA HOME
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'keywords' => $objOrganization->keywords
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_login', $content);
    }

}

