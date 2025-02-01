<?php


require __DIR__  . "/../vendor/autoload.php";

use \App\controllers\pages\Home;

echo Home::homeGetPage("homeContent");
