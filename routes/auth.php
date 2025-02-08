<?php

use \App\controllers\pages\Auth;
use \App\http\Request;
use \App\http\Router;

$request = new Request();

$router = new Router();

Auth::$request = $request;

$router->add("GET", "/Account", Auth::authGetPage("authContent"));
$router->add("POST", "/Account", [Auth::class, 'decideAuth']);

$router->run($request);
