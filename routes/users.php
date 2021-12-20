<?php

use App\Http\Response;
use App\Controller\User;

//ROTA DE LISTAGEM DE USUARIOS
$objRouter->get('/users',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,User::getUsers($request));
    }
    ]);

//ROTA DE CADASTRO DE UM NOVO USUARIO
$objRouter->get('/users/new',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,User::getNewUser($request));
    }
    ]);    

//ROTA DE CADASTRO DE UM NOVO USUARIO (POST)
$objRouter->post('/users/new',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,User::setNewUser($request));
    }
    ]);  
//ROTA DE DISPLAY DE UM NOVO USUARIO
$objRouter->get('/users/{id}/display',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request,$id) {
        return new Response(200,User::getDisplayUser($request,$id));
    }
    ]);
//ROTA DE EDIÇÃO DE UM USUARIO
$objRouter->get('/users/{id}/edit',[
    'middleware' => [
        'required-admin-login'
    ],
    function($request,$id) {
        return new Response(200,User::getEditUser($request,$id));
    }
    ]); 

//ROTA DE EDIÇÃO DE UM USUARIO (POST)
$objRouter->post('/users/{id}/edit',[
    'middleware' => [
        'required-admin-login'
    ],
    function($request,$id) {
        return new Response(200,User::setEditUser($request,$id));
    }
    ]);
    
//ROTA DE EXCLUSAO DE UM USUARIO  
$objRouter->get('/users/{id}/delete',[
    'middleware' => [
        'required-admin-login'
    ],
    function($request,$id) {
        return new Response(200,User::getDeleteUser($request,$id));
    }
    ]);    
    
//ROTA DE EXCLUSAO DE UM USUARIO (POST)
$objRouter->post('/users/{id}/delete',[
    'middleware' => [
        'required-admin-login'
    ],
    function($request,$id) {
        return new Response(200,User::setDeleteUser($request,$id));
    }
    ]);   