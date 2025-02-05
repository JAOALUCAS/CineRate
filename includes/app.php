<?php


require __DIR__  . "/../vendor/autoload.php";

use \App\common\Enviroment;
use \App\utils\View;
use \App\database\Database;

Enviroment::load(__DIR__ . "/../");

define("URL", getenv("URL"));

View::init([
    "URL" => URL
]);

Database::setConfig([
    getenv("DB_NAME"),
    getenv("DB_HOST"),
    getenv("DB_PORT"),
    getenv("DB_USER"),
    getenv("DB_PASS")
]);

ob_start();

// Rotas de home
include __DIR__ . "/../routes/home.php";

// Rotas de account
include __DIR__ . "/../routes/auth.php";
