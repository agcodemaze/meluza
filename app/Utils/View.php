<?php
namespace App\Utils;

class View {

    /**
     * Método responsável por retornar o conteúdo de uma view.
     * @param string
     * @return string
     */

    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }
     
    /**
     * Método responsável por retornar o conteúdo renderizado de uma view.
     * @param string
     * @param array $vars(strings/numericos)
     * @return string
     */ 

    public static function render($view, $vars = []){
        // COUNTEUDO DA VIEW
        $contentView = self::getContentView($view);

        //CHAVES DO ARRAY DE VARIAVEIS
        $keys = array_keys($vars);

        //abaixo usa-se uma funcao anonima para criar um placeholders
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        /*
        //debug --------
        echo "<pre>";   
        print_r($keys);
        echo "<pre>"; 
        exit;
        //debug --------
        */
        
        //RETORNA O CONTEUDO RENDERIZADO 
        return str_replace($keys, array_values($vars), $contentView);
    }
}


?>