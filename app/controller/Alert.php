<?php

namespace App\Controller;

use App\Utils\View;

Class Alert {
    
    /**
     * Metodo responsavel por retornar uma mensagem de error
     * @param string $message
     * @param string
     * 
     */
    public static function getError($message) {
        return View::render('alert/status',[
            'tipo' => 'danger',
            'mensagem' => $message
            ]);
        
    }
    
    /**
     * Metodo responsavel por retornar uma mensagem de sucesso
     * @param string $message
     * @param string
     * 
     */
    public static function getSuccess($message) {
        return View::render('alert/status',[
            'tipo' => 'success',
            'mensagem' => $message
            ]);
        
    }
    
}
