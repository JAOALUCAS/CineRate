<?php

namespace App\controllers\pages;

use \App\utils\View;
use \App\database\Database;
use \App\database\Pagination;

class Category extends Page{
    
    public static function categorysGetPage($view)
    {

        echo "Plural";
        die();

    }

    public static function categoryGetPage($request, $args = [])
    {

        print_r($args);


        echo "oi";
        die();

    }


}