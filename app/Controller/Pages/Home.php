<?php

namespace App\Controller\Pages;
use \App\Utils\View;

class Home {
    /**
    * Metodo responsavel por retornar o conteúdo da Home
    * @return string
    */

    public static function getHome() {
        return View::render('pages/home',[
            'name' => 'Michell Duarte',
            'description' => 'descricao do site mvc',
            'gato' => 'Rex o Gato'
        ]); 
    }
}