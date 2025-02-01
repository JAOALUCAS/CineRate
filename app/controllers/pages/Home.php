<?php

namespace App\controllers\pages;

use \App\controllers\pages\Page;
use \App\Utils\View;

class Home{

    public static function homeGetPage($view)
    {

        $viewName = "pages/" . $view;

        $content = View::getContentView($viewName);

        return Page::callRenderPage("home", "Cinerate - home", $content);

    }

}