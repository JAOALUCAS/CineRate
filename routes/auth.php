<?php

use \App\controllers\pages\Auth;
use \App\http\Request;
use \App\http\Router;

$request = new Request();

$router = new Router();

Auth::$request = $request;

// Rota login/register original
$router->add("GET", "/Account", Auth::authGetPage("authContent"));

// Rota post login/register
$router->add("POST", "/Account", [Auth::class, 'decideAuth']);

// Rota codigo de verificação
$router->add("GET", "/Account/Code", Auth::authGetPage("email/codeVerifyContent"));

// Rota codigo de verificação post
$router->add("POST", "/Account/Code", [Auth::class, 'codeVerify']);

$router->run($request);
