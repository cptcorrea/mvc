<?php

namespace App\Utils;

Class view {
    
     /**
     * Variaveis padroes da view
     * @param array
     */
    private static $vars = [];
    
     /**
     * Metodo responsavel por definir os dados iniciais da classe
     * @param array $vars
     */
    public static function init($vars = []){
        self::$vars = $vars;
    }
    
     /**
     * Metodo responsavel por retornar o conteudo de uma view
     * @param string $view
     * @return string
     */
    private static function getContentView($view) {

        
        $file = __ROOT__.'/resources/views/'.$view.'.html';

        return file_exists($file) ? file_get_contents($file)  : '';
    }
    
    /**
     * Metodo responsavel por retornar o conteudo renderizado de uma view
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []) {
        //CONTEUDO DA VIEW
        $contentView = self::getContentView($view);
        
        //MERGE DE VARIAVEIS DA VIEW
        $vars = array_merge(self::$vars,$vars);
        
       
       
        //CHAVES DO ARRAY DE VARIAVEIS
        $keys = array_keys($vars);
        $func = function($item) {
            return "{{".$item."}}";
        };
        $keys = array_map($func,$keys);
        
         
        //RETORNA O CONTEUDO RENDERIZADO
        return str_replace($keys, array_values($vars),$contentView);
    }
  
    
}