<?php
//DEFINE PATH RAIZ
define('__ROOT__',dirname(__DIR__));


//CARREGAR ARQUIVOS
require_once __ROOT__.'/includes/autoload.php';




use App\Utils\View;
use Env\Environment;
use App\Db\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;
use App\Http\Router;

//CARREGA ARQUIVO ENV
Environment::load(__ROOT__);

//CARREGA ENV DB
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
    );

//DEFINE A CONSTANTE DE URL
define('URL',getenv("URL"));


//DEFINE O VALOR PADRAO DAS VARIAVES
View::init([
    'URL' => URL
    ]);
    
    

//DEFINE O MAPEAMENTO DE MIDDLEWARE
MiddlewareQueue::setMap([
    'maintenance' =>\App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARE PADROES (EXECUTADOS EM TODAS AS ROTAS)
MiddlewareQueue::setDefault([
    'maintenance'
]);


//INICIA O ROUTER
$objRouter = new Router(URL);

//INCLUI AS ROTAS DE LOGIN
require_once __ROOT__.'/routes/login.php';

//INCLUI AS ROTAS DOS USERS
require_once __ROOT__.'/routes/users.php';

//INCLUI AS ROTAS DOS DEPOIMENTOS
require_once __ROOT__.'/routes/client.php';

//EXECUTA AS ROTAS
$objRouter->run()->sendResponse();






