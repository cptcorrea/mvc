<?php

namespace App\Http;

Class Request {

/**
 * Instancia do Router
 * @param typo
 * @return typo
 */    
    private $router;

/**
 * Metodo HTTP da requisicao
 * @bar strin
 */     
    private $httpMethod;

/**
 * URI da pagina
 * @var string
 */    
    private $uri;
 
/**
 * Paramentros da URL ($_GET)
 * @var array
 */        
    private $queryParams = [];

/**
 * variaves recebidas no POST da pagina ($_POST)
 * @var array
 */        
    private $postVars = [];

/**
 * headers da requisicao
 * @var array
 */        
    private $headers = [];
    
/**
 * Metodo de construção da classe
 *
 */    
    public function __construct($router) {
        
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
        
    }
    
/**
 * Metodo responsavel por definir as variaveis do POST
 */ 
  private function setPostVars() {
      //VERIFICA O METODO DA REQUISICAO
      if($this->httpMethod == "GET") return false;
      
      //POST PADRAO
      $this->postVars = $_POST ?? [];
      
      //POST JSON
      $inputRaw = file_get_contents('php://input');
      $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw,true) : $this->postVars;

  }
    


/**
 * Metodo responsavel por definir a URI
 * @param typo
 * @return typo
 */        
    private function setUri() {
        //URI COMPLETA (COM GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        
        //REMOVE GETS DA UTI
        $xUri = explode('?',$this->uri);
        $this->uri = $xUri[0];
        
        
    }

/**
 * Metodo responsavel por retornar a instancia do router
 * @return Router $router
 */        
    public function getRouter() {
        return $this->router;
    }

/**
 * Metodo responsavel por retornar o metodo HTTP da requisição
 * @return string 
 */      
    public function getHttpMethod() {
        return $this->httpMethod;
    }
    
/**
 * Metodo responsavel por retornar os parametros da URL da requição
 * @return array 
 */    
     public function getQueryParams() {
        return $this->queryParams;
    }

/**
 * Metodo responsavel por retornar a URI da requisição
 * @return string
 */        
     public function getUri() {
        return $this->uri;
    }
    
/**
 * Metodo responsavel por retornar os headers da requisição
 * @return array
 */    
     public function getHeaders() {
        return $this->headers;
    }
    
/**
 * Metodo responsavel por retornar as variaveis do POST da requisição 
 * @return array
 */     
     public function getPostVars() {
        return $this->postVars;
    }
    
}
