<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Consultas;

class Home extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getHome() {
        Auth::authCheck(); //verifica se já tem login válido (jwt)
        $objOrganization = new Organization();
        $objConsultas = new Consultas();
        $consultasHoje = $objConsultas->getConsultasHoje(TENANCY_ID);

        /**
         * Comonentes/Scripts que serão carregados na view
         */
        $componentsScriptsHeader = '
            <script src="'.ASSETS_PATH.'js/hyper-config.js"></script>
            <link href="'.ASSETS_PATH.'css/vendor.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
            <link href="'.ASSETS_PATH.'css/icons.min.css" rel="stylesheet" type="text/css" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/pt-br.min.js"></script>
            <link href="https://unpkg.com/vis-timeline/styles/vis-timeline-graph2d.min.css" rel="stylesheet" />
            <script src="https://unpkg.com/vis-timeline/standalone/umd/vis-timeline-graph2d.min.js"></script>
            <link href="'.ASSETS_PATH.'vendor/ad_sweetalert/sweetalert2.min.css" rel="stylesheet">
            <script src="'.ASSETS_PATH.'vendor/ad_sweetalert/sweetalert2.all.min.js"></script>
        ';

        $componentsScriptsFooter = '
            <script src="'.ASSETS_PATH.'js/vendor.min.js"></script>            
            <script src="'.ASSETS_PATH.'js/app.min.js"></script>
            <script src="'.ASSETS_PATH.'utils/alertDelete.js"></script>
            <script src="'.ASSETS_PATH.'utils/scrollPositionReload.js"></script>
        ';

        //VIEW DA HOME
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'consultasHoje' => $consultasHoje,
            'componentsScriptsHeader' => $componentsScriptsHeader,
            'componentsScriptsFooter' => $componentsScriptsFooter
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_home', $content);
    }

    public static function getConsultasByProfissional($DEN_IDDENTISTA) {

        $objConsultas = new Consultas();
        $consultasByProfissional = $objConsultas->getConsultasByProfissional(TENANCY_ID, $DEN_IDDENTISTA);
        return $consultasByProfissional;
    }

    public static function getConsultasByDayProfPredef($DEN_IDDENTISTA, $DIA) {

        $objConsultas = new Consultas();
        $consultasByDayProfPredef = $objConsultas->getConsultasByDayProfPredef(TENANCY_ID, $DEN_IDDENTISTA, $DIA);
        return $consultasByDayProfPredef;
    }

    public static function getConsultasByDayPredef($DIA) {

        $objConsultas = new Consultas();
        $consultasByDayPredef = $objConsultas->getConsultasByDayPredef(TENANCY_ID, $DIA);
        return $consultasByDayPredef;
    }
}

