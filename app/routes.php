<?php

use App\Controllers\LinksController;
use App\Librairies\Router;

return function (Router $router) {
    $router->addRoute('GET', '/', [LinksController::class, 'getAll']);
    $router->addRoute('POST', '/', [LinksController::class, 'insert']);
};
