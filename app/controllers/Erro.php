<?php

namespace App\controllers;

use App\Utils\View;

class Erro{

    /**
     * Método responsável por a pagina de erro
     */
    public static function getErroPage()
    {

        return View::renderPage("pages/erroPages/erro404", [
            "URL" => URL
        ]);

    }

}