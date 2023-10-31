<?php

namespace App;

use App\Librairies\Dotenv;

$dotenv = new Dotenv;
$dotenv->load('../app/');

use App\Controllers\LinksController;

require_once __DIR__ . '/librairies/Route.php';
require_once __DIR__ . '/librairies/Router.php';

require_once __DIR__ . '/controllers/LinksController.php';
