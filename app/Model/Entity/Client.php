<?php

namespace App\Model\Entity;
use \App\Db\Database;

Class Client {

/**
 * ID do client
 * @var integer
 * 
 */    
public $id;

/**
 * Nome do client
 * @var string
 * 
 */   
public $name;

/**
 * email do client
 * @var string
 * 
 */  
public $email;

/**
 * phone do client
 * @var string
 * 
 */  
public $phone;

/**
 * Data de criação do client 
 * @var timestamp
 * 
 */    
public $dt_created;


/**
 * Data de criação do client 
 * @var datetime
 * 
 */    
public $dt_modified;


/**
 * Metodo responsavel por cadastrar um novo cliente no banco de dados  
 * @return boolean
 * 
 */
public function cadastrar(){
        //DEFINE A DATA
        $this->dt_created = date('Y-m-d H:i:s');
        
        //INSERE O CLIENTE NO BANCO DE DADOS
        $this->id = (new Database('client'))->insert([
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'dt_modified'   => $this->dt_created
            ]);
        return true;
    }

/**
 * Metodo responsavel por atualizar os dados do banco com a instancia atual 
 * @return boolean
 * 
 */    
public function atualizar(){
    $this->dt_modified = date('y-m-d');

    return (new Database('client'))->update('id = '.$this->id,[
           'name' => $this->name,
           'email' => $this->email,
           'phone' => $this->phone
           ]);
}

/**
 * Metodo responsavel por excluir um client do banco de dados 
 * @return boolean
 * 
 */    
public function excluir(){
    
    // EXCLUI O DEPOIMENTO DO BANCO DE DADOS    
    return (new Database('client'))->delete('id = '.$this->id);
}

/**
 * Metodo responsavel por retornar um client com base no seu ID
 * @param integer $ID
 * @return client
 * 
 */   
public static function getClientById($id) {
        return self::getClient('id= '.$id)->fetchObject(self::class);
}

/**
 * Metodo responsavel por retornar clientes
 * @param string $where
 * @param string $order
 * @param string $limit
 * @param string $fields
 * @return PDOSStatement
 */
public static function getClient($where = null, $order = null, $limit = null, $fields = "*") {
        
        return (new Database('client'))->select($where,$order,$limit,$fields);
        
    }
    

}