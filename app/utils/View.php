<?php

namespace App\utils;

class View{

    /**
     * Váriaveis dinâmicas
     *
     * @var array
     */
    private static $vars = [];

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
     *  Método responsável por definir os dados iniciais da classe
     * @param array $vars
     */
    public static function init($vars = [])
    {

        self::$vars = $vars;

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
        
        $templateContentView = self::getContentView($view);

        $contentView = self::getContentView($view);

        $vars = array_merge(self::$vars ,$vars);

        $keys = array_keys($vars);
        
        $keys = array_map(function ($item){
            return "{{".$item."}}";
        }, $keys);

        return str_replace($keys, array_values($vars), $templateContentView);

    }

}