<?php

namespace App\Controller;

use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;


Class Login extends Page {
    
    /**
     * Metodo responsavel por retornar a renderização da pagina de login
     * @param Request $request
     * @param string $errorMessage
     * @return string 
     */
    public static function getLogin($request,$errorMessage = null) {
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        
        //CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('login',[
            'status' => $status
            ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPage('Login > CPTDIGITAL', $content);
        
    }
    
    /**
     * Metodo responsavel por definir o login do usuario
     * @param Request $request
     * @return string 
     */
    public static function setLogin($request) {
        //POST VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';
        
        //BUSCA O USUARIO PELO EMAIL
        $objUser = User::getUserByEmail($email);
        if(!$objUser instanceof User) {
            return self::getLogin($request,'E-mail ou senha invalidos');
        } 
        
        //VERIFICA A SENHA DO USUARIO
        if(!password_verify($password,$objUser->password)){
            return self::getLogin($request,'E-mail ou senha invalidos');
            
        }
        
        
        //CRIA A SESSAO DE LOGIN
        SessionAdminLogin::login($objUser);
        
        //REDIRECIONA O USUARIO PARA A HOME DO ADMIN
        $request->getRouter()->redirect('/client');
       
    }
    
    /**
     * Metodo responsavel por deslogar o usuario
     * @param Request $request
     */
    public static function setLogout($request) {
        //DESTROI A SESSAO DE LOGIN
        SessionAdminLogin::logout();
        
        //REDIRECIONA O USUARIO PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/');
        
    }
    
    
    
}