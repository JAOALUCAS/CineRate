<?php

namespace App\controllers\pages;

use \App\Utils\View;
use \App\controllers\Alert;

class Home extends Page{

    public static function homeGetPage($view)
    {

        $viewName = "pages/" . $view;

        $get = false;

        $msg = "";

        if(isset($_SESSION["login_msg"]) || isset($_SESSION["register_msg"])){

            $get = true;

            $msg = isset($_SESSION["login_msg"]) ? "Bem vindo de volta! Sentimos saudadade." : (isset($_SESSION["register_msg"]) ? "Conta criada com sucesso, seja bem vido ao melhor site de filmes!" : "");

        }

        $pageContent = View::renderPage($viewName, [
            "status" => $get ? Alert::getSucess($msg) : ""
        ]);

        unset($_SESSION["login_msg"]);
        
        unset($_SESSION["register_msg"]);

        return parent::callRenderPage("template", "Cinerate - home", $pageContent);

    }

}