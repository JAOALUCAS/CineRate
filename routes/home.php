<?php

use \App\controllers\pages\Home;
use \App\http\Request;
use \App\http\Router;
use \App\controllers\pages\Category;

$request = new Request();

$router = new Router();

$router->add("GET", "/", Home::homeGetPage("homeContent"));
$router->add("GET", "/Ajuda", Home::homeGetPage("helpContent"));
$router->add("GET", "/Privacidade", Home::homeGetPage("privacidadeContent"));
$router->add("GET", "/Termos", Home::homeGetPage("termosContent"));
$router->add("GET", "/Sobre", Home::homeGetPage("sobreContent"));
$router->add("GET", "/Categorias", [Category::class , "categorysGetPage"]);
$router->add("GET", "/Categorias/{categoria}/{page}", [Category::class, "categoryGetPage"]);
$router->run($request);
