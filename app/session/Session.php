<?php

namespace App\session;

class Session{

    /**
     * Método responsável por iniciar a sessão
     */
    public static function init()
    {

        if(session_status() != PHP_SESSION_ACTIVE){

            session_start();

        }

    }

    /**
     * Método responsável por definir as váriaveis de sessão
     */
    public static function setVars($obuser)
    {
        
        self::init();

        $_SESSION["id"] = $obuser["id"];

        $_SESSION["nome"] = $obuser["nome"];

        $_SESSION["email"] = $obuser["email"];
        
        return $_SESSION;

    }

    
    /**
     * Método responsável por destruir a sessão
     */
    public static function logout()
    {
        
        if(session_status() == PHP_SESSION_ACTIVE){

            session_destroy();

        }

    }

}