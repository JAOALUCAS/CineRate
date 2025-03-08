<?php

namespace App\controllers;

use App\Utils\View;

class Alert{

    /**
     * Método responsável por a mensagem de erro
     */   
     public static function getError($message)
     {

        return View::renderPage("pages/alert", [
            "tipo" => "danger",
            "mensagem" => $message,
            "src" => "../../../resources/assets/icons/icons8-reprovado-50.png"
        ]);
 
     }

    /**
     * Método responsável por a mensagem de sucesso
     */
    public static function getSucess($message)
    {

        return View::renderPage("pages/alert", [
            "tipo" => "success",
            "mensagem" => $message,
            "src" => "../../../resources/assets/icons/icons8-aprovado-50.png"
        ]);

    }

    /**
     * Método responsável por retornar a mensagem de alerta
     */
    public static function getAlert($message)
    {

        return View::renderPage("pages/alert", [
            "tipo" => "warning",
            "mensagem" => $message,
            "src" => "../../../resources/assets/icons/icons8-atencao-50.png"
        ]);

    }

}