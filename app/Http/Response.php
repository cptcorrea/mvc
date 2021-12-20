<?php

namespace App\Http;

Class Response {
    
    /**
     * Codigo do Status HTTP
     * @vars integer
     */
    private $httpCode = 200;
    
    /**
     * Headers do Response para a requisicao
     * @vars array
     */
    private $headers = [];
    
    /**
     * Tipo de conteudo que esta sendo retornado 
     * @vars string
     */
    private $contentType = 'text/html';
    
    /**
     * Conteudo do response
     * @vars mixed
     */
    private $content;
    
    /**
     * Metodo responsavel por iniciar a classe e definir os valores
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode,$content,$contentType = 'text/html')
    {
        
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
      
    }
    
    /**
     * Metodo responsavel por alterar o content type da response
     * @param string $contentType
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('content-Type',$contentType);
    }
    
    /**
     * Metodo responsavel por adicionar um registro no header de response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key,$value) {
        $this->headers[$key] =  $value;
    }
    
    
    /**
     * Metodo responsavel por enviar os headers para o navegador
     */
    private function sendHeaders() {
        //STATUS
        http_response_code($this->httpCode);
        
        //ENVIAR HEADERS
        foreach($this->headers as $key=>$value) {
            header($key.': '.$value);
            
        }
    }
    
    /**
     * Metodo responsavel por enviar a resposta para o usuario
     */
    public function sendResponse() {
        //ENVIA OS HEADERS
        $this->sendHeaders();
        
        
        //IMPRIME O CONTEUDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
        
    }
    
}
