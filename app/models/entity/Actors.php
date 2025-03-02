<?php

namespace App\models\entity;

use \App\database\Database;

class Actors{

    /**
     * Método responsável por cadastrar os atores
     */
    public static function cadastrar($dados)
    {

        return (new Database("atores"))->insert([
            "nome" => $dados["nome"],
            "data_nascimento" => isset($dados["data_nascimento"]) ? $dados["data_nascimento"] : null,
            "foto_path" => isset($dados["foto_path"]) ? $dados["foto_path"] : null,
            "genero" => isset($dados["genero"]) ? $dados["genero"] : null,
            "popularidade" => isset($dados["popularidade"]) ? $dados["popularidade"] : null,
            "nacionalidade" => isset($dados["nacionalidade"]) ? $dados["nacionalidade"] : null
        ]);

    }

    /**
     * Método responsável por atualizar os registros de atores
     */
    public static function atualizar()
    {

    }

    /**
     * Método responsável por excluir o registro no banco
     */
    public static function exluir()
    {

    }

    /**
     * Método responsável por retornar os atores pelo nome
     */
    public static function getActorByName()
    {

    }

    
    public static function verificarNomesDuplicados($names)
    {

        return (new Database("atores"))->select("nome IN ($names)");

    }

}