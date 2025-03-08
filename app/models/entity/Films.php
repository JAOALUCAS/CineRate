<?php

namespace app\models\entity;

use \app\database\Database;
use App\models\entity\Admin;

class Films{

    /**
     * Método responsável por cadastrar o filme no banco de dados
     */
    public static function cadastrar($dados)
    {

        return (new Database("filmes"))->insert([
            "titulo" => $dados['titulo'],
            "ano_lancamento" => $dados['ano_lancamento'],
            "data_lancamento" => isset($dados['data_lancamento']) ? $dados['data_lancamento'] : null,
            "descricao" => isset($dados['descricao']) ? $dados['descricao'] : null,
            "tagline" => isset($dados['tagline']) ? $dados['tagline'] : null,
            "duracao" => isset($dados['duracao']) ? $dados['duracao'] : null,
            "nota_generica" => isset($dados['nota_generica']) ? $dados['nota_generica'] : null,
            "votos_generico" => isset($dados['votos_generico']) ? $dados['votos_generico'] : null,
            "nota_cinerate" => isset($dados['nota_cinerate']) ? $dados['nota_cinerate'] : null,
            "votos_cinerate" => isset($dados['votos_cinerate']) ? $dados['votos_cinerate'] : null,
            "generos" => isset($dados['generos']) ? $dados['generos'] : null,
            "elenco" => isset($dados['elenco']) ? $dados['elenco'] : null,
            "diretores" => isset($dados['diretores']) ? $dados['diretores'] : null,
            "empresas" => isset($dados['empresas']) ? $dados['empresas'] : null,
            "paises" => isset($dados['paises']) ? $dados['paises'] : null,
            "orcamento" => isset($dados['orcamento']) ? $dados['orcamento'] : null,
            "bilheteria" => isset($dados['bilheteria']) ? $dados['bilheteria'] : null,
            "poster_url" => isset($dados['poster_url']) ? $dados['poster_url'] : null,
            "trailer_url" => isset($dados['trailer_url']) ? $dados['trailer_url'] : null,
            "streaming_disponivel" => isset($dados['streaming_disponivel']) ? $dados['streaming_disponivel'] : null
        ]);

    }


    /**
     * Método responsável por excluir o filme do banco de dados
     */
    public static function excluir()
    {

    }

    /**
     * Método responsável por atualizar o cadastro de filmes
     */
    public static function atualizar($id, $dados)
    {

        return (new Database("filmes"))->update(" id = ". $id, $dados);

    }

    /**
     * Método responsável por retornar o filme com base no nome
     */
    public static function getFilmByName()
    {


    }

    public static function verificarFilmesDuplicados($titles)
    {

        return (new Database("filmes"))->select("titulo IN ($titles)");

    }

}