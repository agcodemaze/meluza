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

    private static function getContent(){
        return View::render('pages/vw_sample_empty');
    }

    /**
    * Metodo responsavel por retornar o conteúdo da Página Genérica
    * @return string
    */

    public static function getPage($title, $content) {
        return View::render('pages/page',[
            'title' => $title,
            'menu' => self::getMenu(),
            'header' => self::getHeader(),
            'content' => self::getContent(),
            'footer' => self::getFooter()
        ]); 
    }
}