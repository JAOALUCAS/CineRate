<?php

namespace App\utils;

class View{

    /**
     * Método responsável por pegar o conteúdo de uma view
     *
     * @param string $view
     * @return string
     */
    public static function getContentView($view)
    {

        $file = __DIR__ . "/../../resources/" . $view . ".html";

        return file_exists($file) ? file_get_contents($file) : '';

    }

    /**
     * Método responsável por retornar a página renderizada
     *
     * @param string $view
     * @param array $vars
     * @return array
     */
    public static function renderPage($view, $vars = [])
    {
        
        $contentView = self::getContentView($view);

        $keys = array_keys($vars);
        
        $keys = array_map(function ($item){
            return "{{".$item."}}";
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);

    }

}