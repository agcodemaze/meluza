<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class PageLogin {


    /**
     * Método responsável por rederizar o corpo
     * @return string
     */

    private static function getContent($vwPage,$content){
        return View::render($vwPage,$content);
    }

    /**
    * Metodo responsavel por retornar o conteúdo da Página Genérica
    * @return string
    */

    public static function getPage($vwPage, $content) {

        /*
        //debug --------
        echo "<pre>";   
        print_r($profissionais);
        echo "<pre>"; 
        exit;
        //debug --------
      */

        return View::render('pages/vw_login',[
            'title' => $content["title"],
            'description' => $content["description"],
            'site' => $content["description"],
            'keywords' => $content["description"]
        ]); 
    }
}

