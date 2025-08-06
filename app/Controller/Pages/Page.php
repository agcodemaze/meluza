<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class Page {
    /**
    * Metodo responsavel por retornar o conteúdo da Página Genérica
    * @return string
    */

    public static function getPage($title, $content) {
        return View::render('pages/page',[
            'title' => $title,
            'content' => $content
        ]); 
    }
}