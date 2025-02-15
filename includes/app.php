<?php


require __DIR__  . "/../vendor/autoload.php";

use \App\common\Enviroment;
use \App\communication\Email;
use \App\utils\View;
use \App\database\Database;
use \App\controllers\api\Api;
use \App\session\Session;

Session::init();

Enviroment::load(__DIR__ . "/../");

define("URL", getenv("URL"));

View::init([
    "URL" => URL
]);

Database::setConfig(
    getenv("DB_NAME"),
    getenv("DB_HOST"),
    getenv("DB_PORT"),
    getenv("DB_USER"),
    getenv("DB_PASS")
);

Api::setConfig(
    getenv("API_TMDB_TOKEN")
);

Email::init(
    getenv("EMAIL_HOST"),
    getenv("EMAIL_USER"),
    getenv("EMAIL_PASS"),
    getenv("EMAIL_PORT"),
    getenv("EMAIL_SECURE"),
    getenv("EMAIL_CHARSET")
);

ob_start();

// Rotas de home
include __DIR__ . "/../routes/home.php";

// Rotas de account
include __DIR__ . "/../routes/auth.php";
