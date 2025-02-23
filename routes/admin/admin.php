<?php

use \App\controllers\admin\pages\AdminLogin;
use \App\controllers\api\Api;
use \App\http\Request;
use \App\http\Router;

$request = new Request();

$router = new Router();

AdminLogin::$request = $request;

// Rota login panel
$router->add("GET", "/Admin", AdminLogin::loginGetPage());

// Rota login panel post
$router->add("POST", "/Admin", [AdminLogin::class, "setLogin"]);

$router->run($request);