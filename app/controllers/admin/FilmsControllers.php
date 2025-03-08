<?php

namespace App\controllers\admin;

use \App\models\entity\Films;
use \App\models\entity\Admin;
use \Exception;

class FilmsControllers{

    /**
     * Método responsável por filtrar uma sting url
     */
    private static function validateUrlsInsert($str)
    {

        return preg_replace("/[^a-zA-Z0-9\s.,!?\/:_\-]/", "", $str);

    }

    /**
     * Método responsável por validar inputs de caracteres especiais
     */
    private static function validateInputsInsert($str)
    {
        
        return preg_replace("/[^a-zA-Z0-9\s.,!?]/", "", $str);

    }

    /**
     * Método responsável por verificar se o número de inserções é igual ao tamanho do array
     */
    private static function verifyNumInsertions($tamanhoArray, $numInsercoes)
    {
        
        if($tamanhoArray !== $numInsercoes){

            $_SESSION["warning_insercoes"] = "Houve algum erro com o número de insercões, apenas " . $tamanhoArray - $numInsercoes . "foram feitas!";

        }

    }

    /**
     * Verifica se a lista de filmes já está no banco de dados
     */
    private static function verifyFilmDuplicated($jsonsFilm)
    {
        try {

            $valores = [];

            foreach ($jsonsFilm as $film) {

                if ($film) {

                    array_push($valores, $film->title);

                }

            }

            $titlesFormatedArray = array_map(fn($valor) =>
                "'" . str_replace(['"', "'"], '', $valor) . "'"
            , $valores);

            $titlesFormatedString = implode(", ", $titlesFormatedArray);

            $verifyDuplicated = Films::verificarFilmesDuplicados($titlesFormatedString);

            if (count($verifyDuplicated) > 0) {

                foreach ($jsonsFilm as $film) {

                    foreach ($verifyDuplicated as $index => $duplicated) {

                        if ($film->title == $duplicated["titulo"]) {

                            $jsonsFilm = array_splice($jsonsFilm, $index, $index);

                        }

                    }

                }

            }

            return $jsonsFilm;

        } catch (Exception $e) {
        
            
            $_SESSION["api_error"] = "Erro na verificação de filmes: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }
    
    /**
     * Método responsável por inserir os filmes no banco de dados
     */
    public static function insertMovie($jsonsFilm)
    {

        $numInsercoes = 0;

        try {

            if ($jsonsFilm) {

                $dados = [];

                $films = self::verifyFilmDuplicated($jsonsFilm);

                foreach ($films as $json) {

                    if ($json) {

                        $dados = [
                            "titulo" => self::validateInputsInsert(str_replace(['"', "'"], '', $json->title)),
                            "adulto" => isset($json->adult) ? (int) filter_var($json->adult, FILTER_VALIDATE_BOOLEAN) : null,
                            "ano_lancamento" => isset($json->release_date) ? self::validateInputsInsert(date("Y", strtotime($json->release_date))) : null,
                            "data_lancamento" => isset($json->release_date) ? self::validateInputsInsert($json->release_date) : null,
                            "descricao" => isset($json->overview) ? self::validateInputsInsert(str_replace(['"', "'"], '', $json->overview)) : null,
                            "tagline" => isset($json->tagline) ? self::validateInputsInsert(str_replace(['"', "'"], '', $json->tagline)) : null,
                            "duracao" => isset($json->runtime) ? (int) $json->runtime : null,
                            "nota_generica" => isset($json->vote_average) ? (float) $json->vote_average : null,
                            "votos_generico" => isset($json->vote_count) ? (int) $json->vote_count : null,
                            "generos" => isset($json->genres) ? self::validateInputsInsert(implode(", ", array_column($json->genres, "name"))) : null,
                            "elenco" => isset($json->cast) ? self::validateInputsInsert($json->cast) : null,
                            "diretores" => isset($json->crew) ? self::validateInputsInsert($json->crew) : null,
                            "empresas" => isset($json->production_companies) ? self::validateInputsInsert(implode(", ", array_column($json->production_companies, "name"))) : null,
                            "paises" => isset($json->production_countries) ? self::validateInputsInsert(implode(", ", array_column($json->production_countries, "name"))) : null,
                            "orcamento" => isset($json->budget) ? (int) $json->budget : null,
                            "bilheteria" => isset($json->revenue) ? (int) $json->revenue : null,
                            "poster_url" => isset($json->poster_path) ? self::validateUrlsInsert($json->poster_path) : null,
                            "trailer_url" => isset($json->trailer_url) ? self::validateUrlsInsert($json->trailer_url) : null,
                            "streaming_disponivel" => isset($json->homepage) ? self::validateInputsInsert($json->homepage) : null
                        ];                        

                        if (count($dados) > 0 && strlen($dados["titulos"]) !== 0 && strlen($dados["descricao"] !== 0)) {

                            $numInsercoes++;

                            Films::cadastrar($dados);

                            Admin::cadastrarInsercao();

                        }

                    }

                }

                self::verifyNumInsertions(count($jsonsFilm), $numInsercoes);

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao inserir filme: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }
    
    /**
     * Método responsável por verificar se o post de filme já existe no banco de dados
     */
    private static function verifyFilmDuplicatedMan($postFilm)
    {
        try {

            $nomeFormatedString = "'".$postFilm->titulo."'";

            $verifyDuplicated = Films::verificarFilmesDuplicados($nomeFormatedString);
        
            if(count($verifyDuplicated) > 0){

                $postFilm = null;

            }

            return $postFilm;

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao verificar filme: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }
    
    /**
     * Método responsável por inserir o filme via post
     */
    public static function insertMovieMan($postFilm)
    {

        $numInsercoes = 0;

        try {

            if($postFilm){

                $dados = [];

                $verifyDuplicated = self::verifyFilmDuplicatedMan($postFilm);
                
                if($verifyDuplicated){

                    $dados = [
                        "titulo" => self::validateInputsInsert($postFilm->titulo),
                        "ano_lancamento" => preg_split("/-/", $postFilm->datalancamento)[0],
                        "data_lancamento" => $postFilm->datalancamento,
                        "descricao" => self::validateInputsInsert($postFilm->descricao),
                        "tagline" => self::validateInputsInsert($postFilm->tagline),
                        "duracao" => $postFilm->duracao,
                        "nota_generica" => $postFilm->notagenerica,
                        "votos_generico" => $postFilm->votosgenerico,
                        "generos" => self::validateInputsInsert($postFilm->generos),
                        "elenco" => self::validateInputsInsert($postFilm->elenco),
                        "diretores" => self::validateInputsInsert($postFilm->diretor),
                        "empresas" => self::validateInputsInsert($postFilm->empresa),
                        "paises" => self::validateInputsInsert($postFilm->pais),
                        "orcamento" => $postFilm->orcamento,
                        "bilheteria" => $postFilm->bilheteria,
                        "poster_url" => self::validateUrlsInsert($postFilm->poster),
                        "trailer_url" => self::validateUrlsInsert($postFilm->trailer),
                        "streaming_disponivel" => self::validateInputsInsert($postFilm->streaming)
                    ];
                    
                    if(count($dados) > 0){

                        Films::cadastrar($dados);

                        Admin::cadastrarInsercao();

                    }

                }
                
                self::verifyNumInsertions(count($postFilm), $numInsercoes);

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao inserir filme: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }

    
    /**
     * Método responsável por atualizar o registro de filmes
     */
    public static function updateFilm()
    {

        try{

            $dados = [];
            
            $colunas = [
                'titulo',
                'tagline',
                'ano_lancamento',
                'data_lancamento',
                'duracao',
                'nota_generica',
                'votos_generico',
                'nota_cinerate',
                'votos_cinerate',
                'generos',
                'elenco',
                'diretores',
                'empresas',
                'paises',
                'orcamento',
                'bilheteria',
                'poster_url',
                'trailer_url',
                'streaming_disponivel',
                'data_criacao'
            ];                

            foreach($_POST as $key=>$value){

                if(in_array($key, $colunas)){

                    $dados[$key] = self::validateInputsInsert($value);

                }

            }


            if(count($dados) > 0){

                Films::atualizar($_POST["updateFilm"], $dados);
                
                $_SESSION["api_sucess"] = "Atualização realizada com sucesso realizada com sucesso!";

                header("Location: /admin");

                exit();


            }

        }catch(Exception $e){
            
            $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

            header("Location: /admin");

            exit;
            
        }

    }

}