<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class Page {

    /**
     * Método responsável por rederizar o topo da página
     * @return string
     */

    private static function getHeader(){
        return View::render('pages/header');
    }

    /**
     * Método responsável por rederizar o footer da página
     * @return string
     */

    private static function getFooter(){
        return View::render('pages/footer');
    }

    /**
    * Metodo responsavel por retornar o conteúdo da Página Genérica
    * @return string
    */

    public static function getPage($title, $content) {
        return View::render('pages/page',[
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]); 
    }
}