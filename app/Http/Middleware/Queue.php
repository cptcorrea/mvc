<?php

namespace App\Http\Middleware;

Class Queue{
    
    /**
     * Mapeamento de middlewares
     * @var array
     */
    private static $map = [];
    
    /**
     * Mapeamento de middlewares que serão carregados em todas as rotas
     * @var array
     */
    private static $default = [];
    
    /**
     * Fila de middlewares a serem executados
     * @var array
     */
     private $middlewares = [];
    
    /**
     * Funcao de execucao do controlador 
     * @var Closure
     */
    private $controller;
    
    /**
     * Argumentos da funcao do controlador
     * @var array
     */
    private $controllerArgs = [];
    
    /**
     * Metodo responsavel por construir a classe de fila de middlewares
     * @param array $middlewares
     * @param Closure
     * @param array $controllerArgs
     */
    public function __construct($middlewares,$controller,$controllerArgs) {
        $this->middlewares = array_merge(self::$default,$middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    
    }
    
    
    
    
    
    /**
     * Metodo responsavel por definir o mapeamento de middlewares
     * @param array $map
     */
    public static function setMap($map) {
        self::$map = $map;
        
    }
    
    /**
     * Metodo responsavel por definir o mapeamento de middleware default
     * @param array $middlewares
     */
    public static function setDefault($default) {
        self::$default = $default;
    }
    
    /**
     * Metodo responsavel por executar proximo nivel da fila de middleware
     * @param Request $request
     * @return Response
     */
    public function next($request) {
        //VERIFICA SE A FILA ESTA VAZIA
        if(empty($this->middlewares)) return call_user_func_array($this->controller,$this->controllerArgs);
        
        //MIDDLEWARE
        $middleware = array_shift($this->middlewares);
       
        //VERIFICA MAPEAMENTO
        if(!isset(self::$map[$middleware])) {
            throw new \Exception("problemas ao processar o middleware da requisição", 500); 
        }
        
        //NEXT
        $queue = $this;
        
        $next = function($request) use($queue){
            return $queue->next($request);
        };
        
     
        //EXECUTA O MIDDLEWARE
        
        return (new self::$map[$middleware])->handle($request,$next);
        
    }
    
    
    
    
}











