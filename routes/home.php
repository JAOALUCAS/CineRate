<?php

use \App\controllers\pages\Home;
use \App\http\Request;
use \App\http\Router;

$request = new Request();

$router = new Router();
$router->add("GET", "/", Home::homeGetPage("homeContent"));
$router->add("GET", "/Ajuda", Home::homeGetPage("helpContent"));
$router->run($request);
