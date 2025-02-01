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

        return View::getContentView("pages/header");

    }

    /**
     * Método responsável por retornar o rodapé da página
     * 
     * @return string
     */
    private static function getFooter()
    {

        return View::getContentView("pages/footer");

    }

    /**
     * Método responsável por chamar a página renderizada
     *
     * @param string $view
     * @return string
     */
    public static function callRenderPage($view, $title, $content)
    {

        $viewName = "pages/" . $view;

        return View::renderPage($viewName, [
            "title" => $title,
            "header" => self::getHeader(),
            "content" => $content,
            "footer" => self::getFooter()
        ]);

    }

}