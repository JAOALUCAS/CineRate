<?php

namespace App\controllers\admin\pages;

use \App\utils\View;

class Page{

    /**
     * Método responsável por retornar o cabeçalho do panel
     */
    private static function getHeader()
    {

        return View::renderPage("pages/admin/header", [
            "nome" => isset($_SESSION["nome"]) ? $_SESSION["nome"] : "",
            "URL" => URL
        ]);

    }

    /**
     * Método responsável por chamar a página renderizada
     *
     * @param string $view
     * @return string
     */
    public static function callRenderPage($view, $title, $pageContent, $getHeader = true)
    {


        $viewName = "pages/admin/" . $view;

        return View::renderPage($viewName, [
            "title" => $title,
            "header" => $getHeader ? self::getHeader() : "",
            "pageContent" => $pageContent
        ]);
        
    }

}