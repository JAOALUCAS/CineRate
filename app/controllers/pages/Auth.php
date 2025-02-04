<?php

namespace App\controllers\pages;

use \App\database\Database;
use \App\Utils\View;

class Auth extends Page{

    public static function loginInUser($request)
    {

        $obUser = new User($request);
        
        $queryDefine = $request->getPostVars();

        if(isset($queryDefine["action"])){

            $queryDefine == "insert" ? User::insertUser() : User::registerUser();

        }

    }

    public static function authGetPage($view)
    {

        $viewName = "pages/" . $view;

        $pageContent = View::getContentView($viewName);

        return parent::callRenderPage("template", "Cinerate - registrar", $pageContent, $view);

    }

}