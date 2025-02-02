<?php

namespace App\common;

class Enviroment{

    /**
     * Método responsável por permitir acessar as váriaveis de ambientes
     *
     * @param string $dir
     */
    public static function load($dir)
    {

        $lines = file($dir . "./.env");

        foreach($lines as $line){

            putenv(trim($line));

        }

    }

}