<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Librairies\Router;

require_once '../app/bootstrap.php';

$router = new Router;

$routes = require_once '../app/routes.php';

$routes($router);
$router->dispatch();
