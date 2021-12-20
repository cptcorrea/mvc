<?php

namespace App\Session\Admin;

Class Login {
    
    /**
     * Metodo responsavel por iniciar a sessao
     */
    private static function init() {
        
        //VERIFICA SE A SESSAO NAO ESTA ATIVA
        if(session_status() != 2) {
            session_start();
                 
        }
    }
    
    /**
     * Metodo responsavel por criar o login do usuario
     * @param $objUser
     * @return boolean
     */
    public static function login($objUser) {
      //INICIA A SESSAO 
      self::init();
      
     
      //DEFINE A SESSAO DO USUARIO
      $_SESSION['usuario'] = [
          'id' => $objUser->id,
          'email' => $objUser->email
        ];
        
        $_SESSION['time'] = time();
                
        //SUCESSO
        return true;
        
    }
    
    /**
     * Metodo responsavel por verificar se o usuario está logado
     * @return boolean 
     */
    public static function isLogged() {
        //INICIA A SESSAO 
        self::init();
        
        if(isset($_SESSION['usuario']) && (time() - $_SESSION['time']) <1800) {
            return true;
        }
        
    }
    
   
    /**
     * Metodo responsavel por executar o logout do usuario
     * @return boolean 
     */
    public static function logout() {
        //INICIA A SESSAO
        self::init();
        
        //DESLOGA O USUARIO
        session_destroy();
        
        
        //SUCESSO
        return true;
    }
    
    
    
}