<?php

namespace App\controllers;

use App\Utils\View;

class Alert{

    /**
     * Método responsável por a mensagem de erro
     * @param string $message
     * @return string
     */   
     public static function getError($message)
     {
 
        return View::renderPage("pages/alert", [
            "tipo" => "danger",
            "mensagem" => $message
        ]);
 
     }

    /**
     * Método responsável por a mensagem de sucesso
     * @param string $message
     * @return string
     */
    public static function getSucess($message)
    {

        return View::renderPage("pages/alert", [
            "tipo" => "success",
            "mensagem" => $message
        ]);

    }

}