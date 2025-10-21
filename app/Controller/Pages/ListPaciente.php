<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Paciente;

class ListPaciente extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getPaciente() {

        Auth::authCheck(); //verifica se já tem login válido (jwt)

        $objOrganization = new Organization();
        $paciente = new Paciente();
        $listaPacientes = $paciente->getPacientes(TENANCY_ID);
        
        
        
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
            <script src="'.ASSETS_PATH.'vendor/ad_sweetalert/jquery-3.7.0.min.js"></script>
            <link href="'.ASSETS_PATH.'vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
            <script src="'.ASSETS_PATH.'js/hyper-config.js"></script>
            <link href="'.ASSETS_PATH.'css/vendor.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
            <link href="'.ASSETS_PATH.'css/icons.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/ad_sweetalert/sweetalert2.min.css" rel="stylesheet">
            <script src="'.ASSETS_PATH.'vendor/ad_sweetalert/sweetalert2.all.min.js"></script>
            <script src="'.ASSETS_PATH.'js/serviceworkerpwa.js"></script>
            <link rel="manifest" href="/manifest.json">
        ';

        $componentsScriptsFooter = '
            <script src="'.ASSETS_PATH.'js/vendor.min.js"></script>            
            <script src="'.ASSETS_PATH.'vendor/highlightjs/highlight.pack.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/clipboard/clipboard.min.js"></script>
            <script src="'.ASSETS_PATH.'js/hyper-syntax.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net/js/jquery.dataTables.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/datatables.net-select/js/dataTables.select.min.js"></script>
            <script src="'.ASSETS_PATH.'js/app.min.js"></script>
            <script src="'.ASSETS_PATH.'utils/alertDelete.js"></script>
        ';

        //VIEW DA HOME
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'componentsScriptsHeader' => $componentsScriptsHeader,
            'componentsScriptsFooter' => $componentsScriptsFooter,
            'listaPacientes' => $listaPacientes
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_listPacientes', $content);
    }

}

