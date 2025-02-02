<?php


require __DIR__  . "/../vendor/autoload.php";

use \App\controllers\pages\Home;
use \App\common\Enviroment;
use \App\http\Request;
use \App\http\Router;
use \App\utils\View;

Enviroment::load(__DIR__ . "/../");

define("URL", getenv("URL"));

View::init([
    "URL" => URL
]);

$request = new Request();

$router = new Router();
$router->add("GET", "/", Home::homeGetPage("homeContent"));
$router->run($request);

