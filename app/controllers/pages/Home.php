<?php

namespace App\controllers\pages;

use \App\Utils\View;

class Home extends Page{

    public static function homeGetPage($view)
    {

        $viewName = "pages/" . $view;

        $pageContent = View::getContentView($viewName);

        return parent::callRenderPage("template", "Cinerate - home", $pageContent, $view);

    }

}