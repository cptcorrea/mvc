<?php

namespace App\Http\Middleware;

use App\Session\Admin\Login as SessionAdminLogin;

Class RequireAdminLogout {
    
    /**
 * Metodo responsavel por executar o middleware 
 * @param Request $request
 * @param Closure next
 * @return Response
 */
    public function handle($request,$next) {
        
        //VERIFICA SE O USUARIO ESTA LOGADO
        if(SessionAdminLogin::isLogged()) {
            $request->getRouter()->redirect('/admin');
      }
        //CONTINUA A EXECUCAO
        
        return $next($request);
        
    }
    
    
    
    
}








