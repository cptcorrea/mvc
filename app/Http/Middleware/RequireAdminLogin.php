<?php

namespace App\Http\Middleware;

use App\Session\Admin\Login as SessionAdminLogin;

Class RequireAdminLogin {
    
    /**
     * Metodo responsavel para garantir que o usuario esteja logado
     * @param Request $request
     * @param Closure $next
     * @return $next caso esteja logado
     */
     public function handle($request,$next) {
        //VERIFICA SE O USUARIO ESTA LOGADO 
        if(!SessionAdminLogin::isLogged()) {
          $request->getRouter()->redirect('/admin/login');
      }
        
         
          return $next($request);
        
    }
    
    
    
    
}
