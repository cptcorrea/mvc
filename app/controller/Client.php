<?php

namespace App\Controller;
use App\Utils\View;
use App\Model\Entity\Client as EntityClient;
use App\Db\Pagination;


Class Client extends Page {

/**
 * Metodo responsavel por obter a renderização dos itens de client para a pagina
 * @param Request $request
 * @param Pagination $objPagination
 * @return string
 */
private static function getClientItens($request,&$objPagination) {
    //DEPOIMENTOS
    $itens = "";
    
    //QUANTIDADE TOTAL DE REGISTRO    
    $quantidadeTotal = EntityClient::getClient(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        
    //PAGINA ATUAL   
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;
        
    //INSTANCIA DE PAGINAÇÃO
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual,3);
      
    //RESULTADOS DA PAGINA
    $results = EntityClient::getClient(null, 'id DESC',$objPagination->getLimit());
        
    //RENDERIZA O ITEM
    while($objClient = $results->fetchObject(EntityClient::class)) {
        $itens .= View::render('modules/client/item', [
              'id'                  => $objClient->id,
              'name'                => $objClient->name,
              'email'               => $objClient->email,
              'phone'               => $objClient->phone,
              'dt_modified'         => date('d/m/Y', strtotime($objClient->dt_modified))
            ]);
    }
        
    //RETORNA OS DEPOIMENTOS
    return $itens;
    }


/**
 * Metodo responsavel por retornar a mensagem de status
 * @param Request $request
 * @return string
 */
private static function getStatus($request) {
 // QUERY PARAMS    
 $queryParams = $request->getQueryParams();
    
 // STATUS  
 if(!isset($queryParams['status'])) return '';
 
 // MENSAGENS DE STATUS    
 switch ($queryParams['status']) {
         case 'created':
             return Alert::getSuccess('Cliente criado com sucesso!');
             break;
         case 'updated':
             return Alert::getSuccess('Cliente atualizado com sucesso!');
             break;
         case 'deleted':
             return Alert::getSuccess('Cliente excluido com sucesso!');
             break;
     }
     
 }


/**
 * Metodo responsavel por renderizar a view de listagem de clientes
 * @param Request $request
 * @return string
 */    
public static function getClient($request) {

    //CONTEUDO DO NOME        
    $content = View::render('modules/client/index',[
        'itens' => self::getClientItens($request,$objPagination),
        'pagination' => parent::getPagination($request,$objPagination),
        'status' => self::getStatus($request)
        ]);
        
    //RETORNA A PAGINA COMPLETA
    return parent::getPanel('CLIENTES CPTDIGITAL ',$content,'cliente');
}
/**
 * Metodo responsavel por retornar o formulario de cadastro de um novo cliente
 * @param Request $request
 * @return string
 */  
public static function getNewClient($request) {
    
    // CONTEUDO DO FORMULARIO    
    $content = View::render('modules/client/form',[
          'title'=> 'Cadastrar cliente',
          'name' => '',
          'email' => '',
          'phone' => '',
          'status' => ''
        ]);
    
    // RETORNA A PAGINA COMPLETA    
    return parent::getPanel('CADASTRAR CLIENTE CPTDIGITAL ',$content,'cliente');
}
/**
 * Metodo responsavel por cadastrar um novo cliente no banco
 * @param Request $request
 * @return string
 */    
public static function setNewClient($request) {
    
    // POST VARS        
    $postVars = $request->getPostVars();
    
    // NOVA INSTANCIA DE DEPOIMENTO    
    $objClient = new EntityClient;
    $objClient->name = $postVars['name'] ?? '';
    $objClient->email = $postVars['email'] ?? '';
    $objClient->phone = $postVars['phone'] ?? '';
    $objClient->cadastrar();
    
    // REDIRECIONA O USUARIO    
    $request->getRouter()->redirect('/client/'.$objClient->id.'/display?status=created');
}

/**
 * Metodo responsavel por retornar um display de um novo cliente
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function getDisplayClient($request,$id) {
    
    // OBTEM O CLIENTE DO BANCO DE DADOS    
    $objClient = EntityClient::getClientById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objClient instanceof EntityClient) {
              $request->getRouter()->redirect('/client');
          }
    
    // CONTEUDO DO FORMULARIO     
    $content = View::render('modules/client/display',[
              'title'=> 'Display do cliente',
              'name' =>$objClient->name,
              'email' =>$objClient->email,
              'phone' =>$objClient->phone,
              'status' => self::getStatus($request)
              ]);
    
    //RETORNA A PAGINA COMPLETA        
    return parent::getPanel('DISPLAY CLIENTE  CPTDIGITAL ',$content,'cliente');

}
/**
 * Metodo responsavel por retornar o formulario de edicao de um cliente
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function getEditClient($request,$id) {
    
    // OBTEM O CLIENTE DO BANCO DE DADOS    
    $objClient = EntityClient::getClientById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objClient instanceof EntityClient) {
              $request->getRouter()->redirect('/client');
          }
    
    // CONTEUDO DO FORMULARIO     
    $content = View::render('modules/client/edit',[
              'title'=> 'Editar cliente',
              'name' =>$objClient->name,
              'email' =>$objClient->email,
              'phone' =>$objClient->phone,
              'status' => self::getStatus($request)
              ]);
    
    //RETORNA A PAGINA COMPLETA        
    return parent::getPanel('EDITAR CLIENTE  CPTDIGITAL ',$content,'cliente');
}
/**
 * Metodo responsavel por gravar a atualizacao de um cliente
 * @param Request $request
 * @param integer $ID
 * @return string
 */    
public static function setEditClient($request,$id) {
    // OBTEM O DEPOIMENTO DO BANCO DE DADOS       
    $objClient = EntityClient::getClientById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objClient instanceof EntityClient) {
        $request->getRouter()->redirect('/client');
    }
    
    // POST VARS   
    $postVars = $request->getPostVars();
    
    //ATUALIZA A INSTANCIA      
    $objClient->name = $postVars['name'] ?? $objClient->name;
    $objClient->phone = $postVars['phone'] ?? $objClient->phone;
    $objClient->email = $postVars['email'] ?? $objClient->email;
    $objClient->atualizar();
    
    //REDIRECIONA O USUARIO      
    $request->getRouter()->redirect('/client/'.$objClient->id.'/edit?status=updated');
}
/**
 * Metodo responsavel por retornar o formulario de exclusao de um cliente
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function getDeleteClient($request,$id) {
          //OBTEM O CLIENTE DO BANCO DE DADOS       
          $objClient = EntityClient::getClientById($id);
          
          // VALIDA A INSTANCIA
          if(!$objClient instanceof EntityClient) {
              $request->getRouter()->redirect('/client');
          }
         
          // CONTEUDO DO FORMULARIO
          $content = View::render('modules/client/delete',[
              'name' =>$objClient->name,
              'phone' =>$objClient->phone
            ]);
        
          // RETORNA A PAGINA COMPLETA
          return parent::getPanel('EXCLUIR CLIENTE CPTDIGITAL ',$content,'cliente');
    }

/**
 * Metodo responsavel por excluir um cliente
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function setDeleteClient($request,$id) {
    // OBTEM O CLIENTE DO BANCO DE DADOS    
    $objClient = EntityClient::getClientById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objClient instanceof EntityClient) {
              $request->getRouter()->redirect('/client');
    }
    
    // EXCLUI O CLIENTE
    $objClient->excluir();
    
    //REDIRECIONA O USUARIO
    $request->getRouter()->redirect('/client?status=deleted');
        
    }
    
}



