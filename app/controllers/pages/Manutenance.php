<?php

namespace App\controllers\pages;

use \App\utils\View;

class Manutenance extends Page{

    public static function manutenanceGetPage()
    {

        $viewName = "pages/erroPages/manutenance" ;

        $pageContent = View::getContentView($viewName);

        return parent::callRenderPage("template", "Cinerate - Manutenção", $pageContent, false, false);

    }

}