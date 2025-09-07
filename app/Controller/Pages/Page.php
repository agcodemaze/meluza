<?php

namespace App\Controller\Pages;
use \App\Utils\View;
use \App\Model\Entity\Profissionais;

class Page {
    

    /**
     * Método responsável por rederizar o topo da página
     * @return string
     */

    private static function getHeader($page,$content){
        return View::render($page,$content);
    }

    /**
     * Método responsável por rederizar o menu
     * @return string
     */

    private static function getMenu($page,$content){
        return View::render($page,$content);
    }

    /**
     * Método responsável por rederizar o footer da página
     * @return string
     */

    private static function getFooter($page,$content){
        return View::render($page,$content);
    }

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

        $objProfissionais = new Profissionais();
        $profissionais = $objProfissionais->getProfissionais(TENANCY_ID);

        /*
        //debug --------
        echo "<pre>";   
        print_r($profissionais);
        echo "<pre>"; 
        exit;
        //debug --------
      */

        return View::render('pages/page',[
            'title' => $content["title"],
            'componentsScriptsHeader' => $content["componentsScriptsHeader"],
            'componentsScriptsFooter' => $content["componentsScriptsFooter"],
            'profissionais' => $profissionais,
            'menu' => self::getMenu('pages/menu',$content),
            'header' => self::getHeader('pages/header',$content),
            'content' => self::getContent($vwPage,$content),
            'footer' => self::getFooter('pages/footer',$content),
        ]); 
    }
}