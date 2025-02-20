<?php

use \App\controllers\Erro;
use \App\controllers\pages\Home;
use \App\http\Request;
use \App\http\Router;
use \App\controllers\pages\Category;

$request = new Request();

$router = new Router();

// Rota home original
$router->add("GET", "/", Home::homeGetPage("homeContent"));

// Rotas footer (Ajuda, Politica de privacidade, Termos de uso, Sobre)
$router->add("GET", "/Ajuda", Home::homeGetPage("helpContent"));
$router->add("GET", "/Privacidade", Home::homeGetPage("privacidadeContent"));
$router->add("GET", "/Termos", Home::homeGetPage("termosContent"));
$router->add("GET", "/Sobre", Home::homeGetPage("sobreContent"));

// Rotas de categorias
$router->add("GET", "/Categorias", [Category::class , "categorysGetPage"]);
$router->add("GET", "/Categorias/{categoria}/{page}", [Category::class, "categoryGetPage"]);

$router->run($request);
