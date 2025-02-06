<?php

namespace App\models\entity;

use \App\database\Database;

class User{

    public static  function cadastrar(){

        return (new Database("usuarios"))        

    }

    public static function selecionar(){

    }

    public static function excluir(){
        
    }

    public static function atualizar(){

    }

}