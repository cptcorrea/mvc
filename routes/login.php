<?php

use App\Http\Response;
use App\Controller\Login;

//ROTA LOGIN
$objRouter->get('/',[
    'middlewares' => [
        'required-admin-logout'
        ],
    function($request) {
        return new Response(200,Login::getLogin($request));
    }
    ]);
        
//ROTA LOGIN (POST)
$objRouter->post('/',[
    'middlewares' => [
        'required-admin-logout'
        ],
    function($request) {
        return new Response(200,Login::setLogin($request));
    }
    ]);
       
//ROTA LOGOUT 
$objRouter->get('/logout',[
    'middlewares' => [
        'required-admin-login'
        ],
    function($request) {
        return new Response(200,Login::setLogout($request));
    }
    ]);
        