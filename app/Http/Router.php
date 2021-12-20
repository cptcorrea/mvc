<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

Class Router {
    
/**
 * URL completa do projeto (raiz)
 *@var string
 */
private $url = "";

/**
 * Prefixo de todas as rotas
 *@var string
 */  
private $prefix = "";

/**
 * Indice de rotas
 *@var array
 */    
private $routers = [];

/**
 * Instancia de request
 *@var request
 */
private $request;

/**
 *@var padrao contentType
 */
private $contentType = "text/html";

/**
 * Metodo responsavel por iniciar a classe
 * @param string $url
 */  
public function __construct($url) {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    
    }
    
/**
 * Metodo responsavel por alterar o valor do contentType
 * @param string $contentType
 */ 
 public function setContentType($contentType) {
     $this->contentType = $contentType;
     
 }

    
/**
 * Metodo responsavel por definir o prefixo das rotas
 */    
private function setPrefix() {
        //INFORMAÇÕES DA URL ATUAL       
        $parseUrl = parse_url($this->url);
        
        //DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? "";
        
  
    }

/**
 * Metodo responsavel por adicionar uma rota na classe
 * @param string $method
 * @param string $route
 * @param array $params
 */
private function addRoute($method,$route,$params = []) {
    //VALIDACAO DOS PARAMETROS       
    foreach($params as $key=>$value) {
            if($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        
    // MIDDLEWARES DA ROTA  
    $params['middlewares'] = $params['middlewares'] ?? [];
     
    // VARIAVEIS DA ROTA    
    $params['variables'] = [];
         
    //PADRAO DE VALIDAÇÃO DAS VARIAVEIS DAS ROTAS
    $patternVariable = '/{(.*?)}/';
    if(preg_match_all($patternVariable,$route,$matches)) {
            $route = preg_replace($patternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
    }

    //REMOVE BARRA NO FINAL DA ROTA
    $route = rtrim($route,'/');

    //PADRAO DE VALIDACAO DA URL  
    $patternRoute = '/^'.str_replace('/','\/',$route).'$/';
    
    //ADICIONA A ROTA DENTRO DA CLASSE
    $this->routers[$patternRoute][$method] = $params;
    
  
}
    
/**
 * Metodo responsavel por definir uma rota de GET
 * @param string $route
 * @param array $params
 */
public function get($route,$params = []) {
      return $this->addRoute('GET',$route,$params);
        
    }

/**
 * Metodo responsavel por definir uma rota de POST
 * @param string $route
 * @param array $params
 */    
public function post($route,$params = []) {
        return $this->addRoute('POST',$route,$params);
        
    }

/**
 * Metodo responsavel por definir uma rota de PUT
 * @param string $route
 * @param array $params
 */    
public function put($route,$params = []) {
        return $this->addRoute('PUT',$route,$params);
        
    }

/**
 * Metodo responsavel por definir uma rota de DELETE
 * @param string $route
 * @param array $params
 */    
public function delete($route,$params = []) {
        return $this->addRoute('DELETE',$route,$params);
        
    }

/**
 * Metodo responsavel por retornar a URI desconsiderando o prefixo
 * @return string  
 */
public function getUri() {
    //URI DA REQUEST 
    $uri = $this->request->getUri();
    
    //FATIA A URI COM O PREFIXO
    $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri]; 
   
  
    //RETORNA A URI SEM PREFIXO
    return rtrim(end($xUri),'/');
        
}

/**
 * Metodo responsavel por retornar os dados da rota atual
 * @return  array
 */    
private function getRoute() {
    //URI
    $uri = $this->getUri();
    
    //METHOD
    $httpMethod = $this->request->getHttpMethod();
    
 
    //VALIDA AS ROTAS
    foreach($this->routers as $patternRoute=>$methods) {
             //VERIFICA SE A URI BATE O PADRAO
             if(preg_match($patternRoute,$uri,$matches)) {
                //VERIFICA O METODO
                
            
                if(isset($methods[$httpMethod])) {
                    //REMOVE A PRIMEIRA POSICAO
                    unset($matches[0]);
                    
                    //VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                   
                    //RETORNO DOS PARAMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                
                //METODO NAO PERMITIDO/DEFINIDO
                throw new Exception("Metodo nao permitido",405);
            }
        }
        
        //URL NAO ENCONTRADA
        throw new Exception("URL nao encontrada", 404);
    }

/**
 * Metodo responsavel por executar a rota atual
 * @return Response 
 */    
public function run() {
        try{
            
            //OBTEM A ROTA ATUAL
            $route = $this->getRoute();
            
      
            //VERIFICA O CONTROLADOR
            if(!isset($route['controller'])){
                throw new Exception("A URL nao pode ser processada", 500);
                
            }
            
            //ARGUMENTOS DA FUNCAO
            $args = [];
            
            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            
              
            //RETORNA A EXECUCAO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);
        } catch(Exception $e) {
            return new Response($e->getCode(),$this->getErrorMessage($e->getMessage()),$this->contentType);
        }
    }
    
 /**
 * Metodo responsavel por retornar mensagem de erro de acordo com contentType
 * @param string $message
 * @return mixed 
 */ 
 private function getErrorMessage($message) {
    switch($this->contentType) {
        case 'application/json' :
            return [
                 'error' => $message
                 ];
            break;
        default :
            return $message;
            break;
     }
 }

/**
 * Metodo reponsavel por retorna a URL atual
 * @return string 
 */    
public function getCurrentUrl() {
        return $this->url.$this->getUri();
    }
    
/**
 * Metodo responsavel por redirecionar a URL
 * @param string $route
 */    
public function redirect($route) {
        
        //URL
        $url = $this->url.$route;
        
        //EXECUTA O REDIRECT
        header('location: '.$url);
        exit;
        
        
        
    }
 
}