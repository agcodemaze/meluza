<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getHome() {

        $objOrganization = new Organization();

        /*
        //debug --------
        echo "<pre>";   
        print_r($objOrganization);
        echo "<pre>"; 
        exit;
        //debug --------
        */

        //VIEW DA HOME
        $content = View::render('pages/home',[
            'name' => $objOrganization->name,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site
        ]); 

        //VIEW DA PAGINA
        return self::getPage('Mics page', $content);
    }
}

class teste {
    public static function getteste()
    {
        return "uhull";
    }
    
}