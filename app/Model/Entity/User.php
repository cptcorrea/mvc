<?php

namespace App\Model\Entity;
use \App\Db\Database;

Class User {

/**
 * ID do usuario
 * @var integer
 * 
 */    
public $id;

/**
 * E-mail do usuario
 * @var string
 * 
 */  
public $email;

/**
 * Senha do usuario 
 * @var string
 * 
 */    
public $password;

/**
 * Senha do usuario confirmação 
 * @var string
 * 
 */    
public $password_confirmation;

/**
 * Data de criação do usuario
 * @var timestamp
 * 
 */    
public $dt_created;

/**
 * Metodo responsavel por cadastrar um novo usuario do banco de dados  
 * @return boolean
 * 
 */
public function cadastrar(){
        $this->dt_created = date('Y-m-d H:i:s');
        
        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('user'))->insert([
            'email'                     => $this->email,
            'password'                  => $this->password,
            'password_confirmation'     => $this->password_confirmation
            ]);
            
        //SUCESSO    
        return true;
    }

/**
 * Metodo responsavel por atualizar os dados do banco 
 * @return boolean
 * 
 */    
public function atualizar(){
        
    return (new Database('user'))->update('id = '.$this->id,[
           'email'                  => $this->email,
           'password'               => $this->password,
           'password_confirmation'  => $this->password_confirmation,
           ]);
}

/**
 * Metodo responsavel por excluir os dados do banco 
 * @return boolean
 * 
 */    
public function excluir(){
    
    // EXCLUI O USUARIO DO BANCO DE DADOS    
    return (new Database('user'))->delete('id = '.$this->id);
}

/**
 * Metodo responsavel por retornar um usuario com base em seu e-mail
 * @param string $email
 * @return User
 * 
 */   
public static function getUserByEmail($email) {
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
}

/**
 * Metodo responsavel por retornar Usuarios 
 * @param string $where
 * @param string $order
 * @param string $limit
 * @param string $fields
 * @return PDOSStatement
 */
public static function getUsers($where = null, $order = null, $limit = null, $fields = "*") {
        
        return (new Database('user'))->select($where,$order,$limit,$fields);
        
    }
    
/**
 * Metodo responsavel por retornar um usuario com base em seu ID
 * @param integer ID
 * @return User
 * 
 */   
public static function getUserById($id) {
        return self::getUsers('id = '.$id)->fetchObject(self::class);
}
    
    
    

}