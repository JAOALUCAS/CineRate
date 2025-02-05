<?php

namespace App\controllers\pages;

use \App\database\Database;
use \App\Utils\View;

class Auth extends Page{

    public static function loginInUser($request)
    {

        
        $queryDefine = $request->getPostVars();

        echo "<pre>";
        print_r($queryDefine);
        echo "</pre>";
        die();

    }

    public static function authGetPage($view)
    {

        $viewName = "pages/" . $view;

        $pageContent = View::getContentView($viewName);

        return parent::callRenderPage("template", "Cinerate - registrar", $pageContent, $view);

    }

}