<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class Home extends Page{
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getHome() {

        //VIEW DA HOME
        $content = View::render('pages/home',[
            'name' => 'Michell Duarte',
            'description' => 'descricao do site mvc',
            'gato' => 'Rex o Gato'
        ]); 

        //VIEW DA PAGINA
        return self::getPage('Mics page', $content);
    }
}