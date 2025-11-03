<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Utils\Auth;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Anamnese;
use \App\Controller\Pages\S3Controller;

class EditModeloAnamnese extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getModeloAnamnese($id) {

        Auth::authCheck(); //verifica se já tem login válido (jwt)

        $objOrganization = new Organization();
        $anamnese = new Anamnese();
        $anamneseInfo = $anamnese->getModeloAnamneseModelById($id,TENANCY_ID);
        
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
            'anamneseInfo' => $anamneseInfo
        ]); 

        //VIEW DA PAGINA
        return self::getPage('pages/vw_editModeloAnamnese', $content);
    }

    public static function updateModeloAnemnese($ANM_JSON_MODELO, $ANM_DCTITULO, $ANM_DCIDIOMA, $ANM_IDANAMNESE_MODELO) {
        header('Content-Type: application/json; charset=utf-8');
        Auth::authCheck(); //verifica se já tem login válido (jwt)

        $objOrganization = new Organization();
        $anamnese = new Anamnese();
        $anamneseModelo = $anamnese->updateModeloAnamneseById($ANM_IDANAMNESE_MODELO, $ANM_JSON_MODELO, $ANM_DCTITULO, $ANM_DCIDIOMA, TENANCY_ID);
        
        return $anamneseModelo;
    }

    public static function deleteModeloAnemnese($ANM_IDANAMNESE_MODELO) {
        header('Content-Type: application/json; charset=utf-8');
        Auth::authCheck(); //verifica se já tem login válido (jwt)

        $objOrganization = new Organization();
        $anamnese = new Anamnese();
        $anamneseModelo = $anamnese->deleteModeloAnamneseById($ANM_IDANAMNESE_MODELO, TENANCY_ID);
        
        return $anamneseModelo;
    }

}

