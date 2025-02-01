<?php

namespace App\controllers\pages;

use \App\controllers\pages\Page;
use \App\Utils\View;

class Home{

    public static function homeGetPage($view)
    {

        $viewName = "pages/" . $view;

        $pageContent = View::getContentView($viewName);

        return Page::callRenderPage("template", "Cinerate - home", $pageContent, $view);

    }

}