<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Anamnese;
use \App\Controller\Pages\S3Controller;

class CadModeloAnamnese extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getModeloAnamnese() {

        Auth::authCheck(); //verifica se já tem login válido (jwt)

        $objOrganization = new Organization();
        $anamnese = new Anamnese();
        $listaAnamnese = $anamnese->getAllAnamneseModel(TENANCY_ID);
        
        /**
         * Comonentes/Scripts que serão carregados na view
         */
        $componentsScriptsHeader = '
            <script src="'.ASSETS_PATH.'vendor/ad_sweetalert/jquery-3.7.0.min.js"></script>
            <script src="'.ASSETS_PATH.'js/hyper-config.js"></script>
            <link href="'.ASSETS_PATH.'css/vendor.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
            <link href="'.ASSETS_PATH.'css/icons.min.css" rel="stylesheet" type="text/css" />
            <link href="'.ASSETS_PATH.'vendor/ad_sweetalert/sweetalert2.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/ad_sweetalert/sweetalert2.all.min.js"></script>
            <script src="'.ASSETS_PATH.'js/serviceworkerpwa.js"></script>
            <link rel="manifest" href="/manifest.json">
        ';

        $componentsScriptsFooter = '
            <script src="'.ASSETS_PATH.'js/vendor.min.js"></script>            
            <script src="'.ASSETS_PATH.'vendor/highlightjs/highlight.pack.min.js"></script>
            <script src="'.ASSETS_PATH.'vendor/clipboard/clipboard.min.js"></script>
            <script src="'.ASSETS_PATH.'js/hyper-syntax.js"></script>
            <script src="'.ASSETS_PATH.'js/app.min.js"></script>
        ';

        //VIEW DA HOME
        $content = ([
            'title' => $objOrganization->title,
            'description' => $objOrganization->description,
            'site' => $objOrganization->site,
            'componentsScriptsHeader' => $componentsScriptsHeader,
            'componentsScriptsFooter' => $componentsScriptsFooter,
            'listaAnamnese' => $listaAnamnese
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_cadModeloAnamnese', $content);
    }

    public static function cadModeloAnemnese($ANM_JSON_MODELO, $ANM_DCTITULO, $ANM_DCIDIOMA) {
        header('Content-Type: application/json; charset=utf-8');
        Auth::authCheck(); //verifica se já tem login válido (jwt)

        $objOrganization = new Organization();
        $anamnese = new Anamnese();
        $anamneseModelo = $anamnese->insertModeloAnemnese($ANM_JSON_MODELO, $ANM_DCTITULO, $ANM_DCIDIOMA, TENANCY_ID);
        
        return $anamneseModelo;
    }

}

