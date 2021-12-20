<?php

namespace App\Http\Middleware;

use \Exception;

Class Maintenance {
    


/**
 * Metodo responsavel por executar o middleware 
 * @param Request $request
 * @param Closure next
 * @return Response
 */
public function handle($request,$next) {
    
   
    //VERIFICA O ESTADO DE MANUTENCAO DA PAGINA
    if(getenv('MAINTENANCE') == 'true') {
          throw new \Exception("Pagina em manutenção, Tente mais tarde.",200);
      }
       
       //EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
       return $next($request);
        
}
}






