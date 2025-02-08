<?php

namespace App\session;

class Session{

    private static function init()
    {

        if(session_status() != PHP_SESSION_ACTIVE){

            session_start();

        }

    }

    public static function setVars($paramas)
    {
        
        self::init();

        $_SESSION["id"] = $paramas["id"];

        $_SESSION["nome"] = $paramas["nome"];

        $_SESSION["email"] = $paramas["email"];

    }


}