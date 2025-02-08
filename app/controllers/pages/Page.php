<?php

namespace App\controllers\pages;

use \App\utils\View;

class Page{

    /**
     * Método responsável por retornar o cabeçalho da página
     * 
     * @return string
     */
    private static function getHeader()
    {

        return View::renderPage("pages/header");

    }

    /**
     * Método responsável por retornar o rodapé da página
     * 
     * @return string
     */
    private static function getFooter()
    {

        return View::renderPage("pages/footer");

    }

    /**
     * Método responsável por chamar a página renderizada
     *
     * @param string $view
     * @return string
     */
    public static function callRenderPage($view, $title, $pageContent, $css)
    {

        $viewName = "pages/" . $view;

        return View::renderPage($viewName, [
            "title" => $title,
            "cssSpecific" => $css,
            "header" => self::getHeader(),
            "pageContent" => $pageContent,
            "footer" => self::getFooter()
        ]);

    }

}