<?php

namespace App\controllers\pages;

use \App\database\Database;
use \App\Utils\View;
use \App\models\entity\User;

class Auth extends Page{

    public static function loginInUser($request)
    {

        
        $queryDefine = $request->getPostVars();

        if(isset($queryDefine["nome"])){

            $result =  User::cadastrar();

        }

        $result =  User::selecionar();

        echo "<pre>";
        print_r($result);
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