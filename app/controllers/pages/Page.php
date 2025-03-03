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

        $url = URL;

        $account = "<a href='$url/Account'>Entrar</a>";

        if (session_status() !== PHP_SESSION_NONE && isset($_SESSION['id'])){

            $nome = $_SESSION["nome"];

            $id = $_SESSION["id"];
            
            $account = "

                <a href='$url/Account/$id' id='userName'>$nome</a>

                <div class='settings disabled'>

                    <ul>

                        <li>

                            <a href='$url/Account/$id'>

                                <img src='../../resources/assets/icons/icons8-person-48.png'>

                                <p>Perfil</p>

                            </a>

                        </li>
                        
                        <li>

                            <a href='$url/Account/logout'>

                                <img src='../../resources/assets/icons/icons8-sair-50.png'>

                                <p>Logout</p>

                            </a>
                        
                        </li>

                        <li>

                            <a href='$url/Account/config'>

                                <img src='../../resources/assets/icons/icons8-engrenagem-48.png'>

                                <p>Config</p>

                            </a>

                        </li>

                    </ul>

                </div>

            ";

        }

        return View::renderPage("pages/header", [
            "account" => $account
        ]);

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
    public static function callRenderPage($view, $title, $pageContent, $getHeader = true, $getFooter = true)
    {

        $viewName = "pages/" . $view;

        return View::renderPage($viewName, [
            "title" => $title,
            "header" => $getHeader ? self::getHeader() : "",
            "pageContent" => $pageContent,
            "footer" => $getFooter ? self::getFooter() : ""
        ]);

    }

}