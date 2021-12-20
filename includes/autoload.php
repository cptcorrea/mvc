<?php
// carregar os modulos & classes

require_once __ROOT__.'/app/controller/Page.php';
require_once __ROOT__.'/app/controller/Login.php';
require_once __ROOT__.'/app/controller/Alert.php';
require_once __ROOT__.'/app/controller/Client.php';
require_once __ROOT__.'/app/controller/User.php';
require_once __ROOT__.'/app/Session/Login.php';
require_once __ROOT__.'/app/Utils/view.php';
require_once __ROOT__.'/app/Model/Entity/Client.php';
require_once __ROOT__.'/app/Model/Entity/User.php';
require_once __ROOT__.'/app/Http/Request.php';
require_once __ROOT__.'/app/Http/Response.php';
require_once __ROOT__.'/app/Http/Router.php';
require_once __ROOT__.'/env/Environment.php';
require_once __ROOT__.'/app/Db/Database.php';
require_once __ROOT__.'/app/Db/Pagination.php';
require_once __ROOT__.'/app/Http/Middleware/Queue.php';
require_once __ROOT__.'/app/Http/Middleware/Maintenance.php';
require_once __ROOT__.'/app/Http/Middleware/RequireAdminLogin.php';
require_once __ROOT__.'/app/Http/Middleware/RequireAdminLogout.php';




?>