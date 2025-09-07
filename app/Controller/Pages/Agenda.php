<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Agenda extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getAgenda () {

        $objOrganization = new Organization();

        /*
        //debug --------
        echo "<pre>";   
        print_r($convenios);
        echo "<pre>"; 
        exit;
        //debug --------
        */


        /**
         * Comonentes/Scripts que serão carregados na view
         */
        $componentsScriptsHeader = '
            <script src="'.ASSETS_PATH.'js/hyper-config.js"></script>
            <link href="'.ASSETS_PATH.'css/vendor.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
            <link href="'.ASSETS_PATH.'css/icons.min.css" rel="stylesheet" type="text/css" />
            <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/pt-br.global.min.js"></script>
        ';

        $componentsScriptsFooter = '
            <script src="'.ASSETS_PATH.'js/vendor.min.js"></script>    
            <script src="'.ASSETS_PATH.'vendor/fullcalendar/index.global.min.js"></script>                   
            <script src="'.ASSETS_PATH.'js/app.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/jquery-mask-plugin/jquery.mask.min.js"></script>
        ';

        //VIEW DA HOME
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'componentsScriptsHeader' => $componentsScriptsHeader,
            'componentsScriptsFooter' => $componentsScriptsFooter
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_agenda', $content);
    }

}

