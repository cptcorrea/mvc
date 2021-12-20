<?php

use App\Http\Response;
use App\Controller\Client;

//ROTA DE LISTAGEM DOS CLIENTES
$objRouter->get('/client',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,Client::getClient($request));
    }
    ]);

//ROTA DE CADASTRO DE UM NOVO CLIENT
$objRouter->get('/client/new',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,Client::getNewClient($request));
    }
    ]);    

//ROTA DE CADASTRO DE UM NOVO CLIENT (POST) 
$objRouter->post('/client/new',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,Client::setNewClient($request));
    }
    ]);  
//ROTA DE DISPLAY DE UM NOVO CLIENTE
$objRouter->get('/client/{id}/display',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id) {
        return new Response(200,Client::getDisplayClient($request,$id));
    }
    ]);

//ROTA DE EDIÇÃO DE UM CLIENT
$objRouter->get('/client/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request,$id) {
        return new Response(200,Client::getEditClient($request,$id));
    }
    ]); 

//ROTA DE EDICAO DE UM CLIENT (POST)
$objRouter->post('/client/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request,$id) {
        return new Response(200,Client::setEditClient($request,$id));
    }
    ]);
    
//ROTA DE EXCLUSAO DE UM CLIENT
$objRouter->get('/client/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request,$id) {
        return new Response(200,Client::getDeleteClient($request,$id));
    }
    ]);    
    
//ROTA DE EXCLUSAO DE UM CLIENT (POST)
$objRouter->post('/client/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request,$id) {
        return new Response(200,Client::setDeleteClient($request,$id));
    }
    ]);   