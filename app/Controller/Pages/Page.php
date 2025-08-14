<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class Page {

    /**
     * Método responsável por rederizar o topo da página
     * @return string
     */

    private static function getHead(){
        return View::render('pages/head');
    }

    /**
     * Método responsável por rederizar o menu
     * @return string
     */

    private static function getMenu(){
        return View::render('pages/menu');
    }

    /**
     * Método responsável por rederizar o footer da página
     * @return string
     */

    private static function getFooter(){
        return View::render('pages/footer');
    }

    /**
     * Método responsável por rederizar o corpo
     * @return string
     */

    private static function getMain(){
        return View::render('pages/main');
    }

    /**
    * Metodo responsavel por retornar o conteúdo da Página Genérica
    * @return string
    */

    public static function getPage($title, $content) {
        return View::render('pages/page',[
            'title' => $title,
            'header' => self::getHead(),
            'content' => $content,
            'footer' => self::getFooter()
        ]); 
    }
}