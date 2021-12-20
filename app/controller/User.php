<?php

namespace App\Controller;
use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use App\Db\Pagination;


Class User extends Page {

/**
 * Metodo responsavel por obter a renderização dos itens de usuarios para a pagina
 * @param Request $request
 * @param Pagination $objPagination
 * @return string
 */
private static function getUserItens($request,&$objPagination) {
    //USUARIOS
    $itens = "";
    
    //QUANTIDADE TOTAL DE REGISTRO    
    $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
    
    //PAGINA ATUAL   
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;
        
    //INSTANCIA DE PAGINAÇÃO
    $objPagination = new Pagination($quantidadeTotal, $paginaAtual,3);
      
    //RESULTADOS DA PAGINA
    $results = EntityUser::getUsers(null, 'id DESC',$objPagination->getLimit());
        
    //RENDERIZA O ITEM
    while($objUser = $results->fetchObject(EntityUser::class)) {
        $itens .= View::render('modules/users/item', [
              'id'        => $objUser->id,
              'email'    => $objUser->email,
              'dt_created' => $objUser->dt_created
            ]);
    }
        
    //RETORNA OS USUARIOS
    return $itens;
    }

/**
 * Metodo responsavel por renderizar a view de listagem de usuarios
 * @param Request $request
 * @return string
 */    
public static function getUsers($request) {

    //CONTEUDO DO NOME        
    $content = View::render('modules/users/index',[
        'itens' => self::getUserItens($request,$objPagination),
        'pagination' => parent::getPagination($request,$objPagination),
        'status' => self::getStatus($request)
        ]);
        
    //RETORNA A PAGINA COMPLETA
    return parent::getPanel('USUARIOS CPTDIGITAL ',$content,'usuario');
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
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluido com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail digitado já está sendo utilizado por outro usuário.');
                break;
            case 'different':
                return Alert::getError('As senha são diferentes');
        }
        
    }

/**
 * Metodo responsavel por retornar o formulario de cadastro de um novo usuario
 * @param Request $request
 * @return string
 */  
public static function getNewUser($request) {
    
    // CONTEUDO DO FORMULARIO    
    $content = View::render('modules/users/form',[
          'title'=> 'Cadastrar usuário',
          'email' => '',
          'password' => '',
          'password_confirmation' => '',
          'status' => self::getStatus($request)
        ]);
    
    // RETORNA A PAGINA COMPLETA    
    return parent::getPanel('CADASTRAR USUARIO CPTDIGITAL ',$content,'usuario');
}

/**
 * Metodo responsavel por cadastrar um novo usuario no banco
 * @param Request $request
 * @return string
 */    
public static function setNewUser($request) {
    
    // POST VARS        
    $postVars = $request->getPostVars();
    $email = $postVars['email'] ?? '';
    $password = $postVars['password'] ?? '';
    $password_confirmation = $postVars['password_confirmation'] ?? '';
    
    //VALIDA E E-MAIL DO USUARIO
    $objUser = EntityUser::getUserByEmail($email);
    if($objUser instanceof EntityUser) {
        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/users/new?status=duplicated');
    }

    if($password <> $password_confirmation) {
        $request->getRouter()->redirect('/users/new?status=different');
    }
   
    // NOVA INSTANCIA DE USUARIO  
    $objUser = new EntityUser;
    $objUser->email = $email;
    $objUser->password = password_hash($password,PASSWORD_DEFAULT);
    $objUser->password_confirmation = password_hash($password_confirmation,PASSWORD_DEFAULT);
    $objUser->cadastrar();
    
    // REDIRECIONA O USUARIO    
    $request->getRouter()->redirect('/users/'.$objUser->id.'/display?status=created');
}

/**
 * Metodo responsavel por retornar de um display de um usuario
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function getDisplayUser($request,$id) {
    
    // OBTEM O USUARIO DO BANCO DE DADOS    
    $objUser = EntityUser::getUserById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objUser instanceof EntityUser) {
              $request->getRouter()->redirect('/users');
          }
    
    // CONTEUDO DO FORMULARIO     
    $content = View::render('modules/users/display',[
              'title'=> 'Display usuario',
              'email' => $objUser->email,
              'dt_created' => $objUser->dt_created,
              'status' => self::getStatus($request)
              ]);
    
    //RETORNA A PAGINA COMPLETA        
    return parent::getPanel('EDITAR USUARIO CPTDIGITAL ',$content,'usuario');
} 
/**
 * Metodo responsavel por retornar o formulario de edicao de um usuario
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function getEditUser($request,$id) {
    
    // OBTEM O USUARIO DO BANCO DE DADOS    
    $objUser = EntityUser::getUserById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objUser instanceof EntityUser) {
              $request->getRouter()->redirect('/users');
          }
    
    // CONTEUDO DO FORMULARIO     
    $content = View::render('modules/users/edit',[
              'title'=> 'Editar usuario',
              'email' => $objUser->email,
              'status' => self::getStatus($request)
              ]);
    
    //RETORNA A PAGINA COMPLETA        
    return parent::getPanel('EDITAR USUARIO CPTDIGITAL ',$content,'usuario');
}
    
/**
 * Metodo responsavel por gravar a atualizacao de um usuario
 * @param Request $request
 * @param integer $ID
 * @return string
 */    
public static function setEditUser($request,$id) {
     // OBTEM O USUARIO DO BANCO DE DADOS    
     $objUser = EntityUser::getUserById($id);
    
     // VALIDA A INSTANCIA      
     if(!$objUser instanceof EntityUser) {
              $request->getRouter()->redirect('/users');
          }
    
    // POST VARS   
    $postVars = $request->getPostVars();
    $password = $postVars['password'] ?? '';
    $password_confirmation = $postVars['password_confirmation'] ?? '';
    
     //VALIDA AS SENHAS 
    if($password <> $password_confirmation) {
        //REDIRECIONA O USUARIO
        $request->getRouter()->redirect('/users/'.$id.'/edit?status=different');
    }
   
    //ATUALIZA A INSTANCIA      
    $objUser->password = password_hash($password,PASSWORD_DEFAULT);
    $objUser->password_confirmation = password_hash($password_confirmation,PASSWORD_DEFAULT);
    $objUser->atualizar();
    
    //REDIRECIONA O USUARIO      
    $request->getRouter()->redirect('/users/'.$objUser->id.'/edit?status=updated');
}
    
/**
 * Metodo responsavel por retornar o formulario de exclusao de um usuario
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function getDeleteUser($request,$id) {
    // OBTEM O USUARIO DO BANCO DE DADOS    
    $objUser = EntityUser::getUserById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objUser instanceof EntityUser) {
          $request->getRouter()->redirect('/users');
    }
    
         
    // CONTEUDO DO FORMULARIO
    $content = View::render('modules/users/delete',[
              'email' => $objUser->email
            ]);
        
    // RETORNA A PAGINA COMPLETA
    return parent::getPanel('EXCLUIR USUARIO CPTDIGITAL ',$content,'usuario');
}

/**
 * Metodo responsavel por excluir um usuario
 * @param Request $request
 * @param integer $ID
 * @return string
 */
public static function setDeleteUser($request,$id) {
    // OBTEM O USUARIO DO BANCO DE DADOS    
    $objUser = EntityUser::getUserById($id);
    
    // VALIDA A INSTANCIA      
    if(!$objUser instanceof EntityUser) {
          $request->getRouter()->redirect('/users');
    }
    
    
    // EXCLUI O USUARIO
    $objUser->excluir();
    
    //REDIRECIONA O USUARIO
    $request->getRouter()->redirect('/users?status=deleted');
        
    }
    
}



