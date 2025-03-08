<?php

namespace App\controllers\admin;

use \App\models\entity\Actors;
use \App\models\entity\Admin;
use \Exception;

class ActorsControllers{

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
     * Verifica se a lista de atores já está no banco de dados
     */
    private static function verifyActorDuplicated($jsonsActor)
    {

        try {

            $valores = [];

            foreach($jsonsActor as $actor){

                array_push($valores, $actor->name);

            }

            $namesFormatedArray = array_map(fn($valor) => $valor = "'" . $valor . "'", $valores);

            $namesFormatedString = implode(", ", $namesFormatedArray);

            $verifyDuplicated = Actors::verificarNomesDuplicados($namesFormatedString);

            if(count($verifyDuplicated) > 0){

                foreach($jsonsActor as $actor){

                    foreach($verifyDuplicated as $index => $duplicated){

                        if($actor->name == $duplicated["nome"]){

                            $jsonsActor = array_splice($jsonsActor, $index, 1);

                        }

                    }

                }

                return $jsonsActor;
            }

            return $jsonsActor;

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao verificar atores duplicados: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }
    
    /**
     * Método responsável por inserir os atores no banco de dados
     */
    public static function insertActor($jsonsActor)
    {

        $numInsercoes = 0;

        try {

            if($jsonsActor){

                $dados = [];

                $actors = self::verifyActorDuplicated($jsonsActor);

                foreach($actors as $json){

                    if($json){

                        $dados = [
                            "nome" => self::validateInputsInsert($json->name),
                            "popularidade" => isset($json->popularity) ? self::validateInputsInsert($json->popularity) : null,
                            "genero" => isset($json->gender) ? ($json->gender == 1 ? "feminino" : "masculino") : null,
                            "foto_path" => isset($json->profile_path) ? self::validateUrlsInsert($json->profile_path) : null,
                        ];

                        if(count($dados) > 0 && strlen($dados["nome"]) !== 0){

                            $numInsercoes++;

                            Actors::cadastrar($dados);

                            Admin::cadastrarInsercao();
                            
                        }
                    }

                }

                self::verifyNumInsertions(count($jsonsActor), $numInsercoes);

                return false;

            }

            return true;

        } catch (Exception $e) {

            $_SESSION["api_error"] = "Erro ao inserir atores: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }
    
    /**
     * Método responsável por verificar se a inserção de post do ator já existe
     */
    private static function verifyActorDuplicatedMan($postActor)
    {

        try {

            $nomeFormatedString = "'".$postActor->nome."'";

            $verifyDuplicated = Actors::verificarNomesDuplicados($nomeFormatedString);

            if(count($verifyDuplicated) > 0){

                $postActor = null;
            }

            return $postActor;

        } catch (Exception $e) {

            $_SESSION["api_error"] = "Erro ao verificar ator duplicado: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }

    /**
     * Método responsável por inserir o ator via post
     */
    public static function insertActorMan($postActor)
    {

        $numInsercoes = 0;

        try {

            if($postActor){

                $dados = [];

                $verifyDuplicated = self::verifyActorDuplicatedMan($postActor);

                if($verifyDuplicated){

                    $dados = [
                        "nome" => self::validateInputsInsert($postActor->nome),
                        "data_nascimento" => $postActor->datanascimento,
                        "nacionalidade" => self::validateInputsInsert($postActor->pais),
                        "genero" => self::validateInputsInsert($postActor->genero),
                        "foto_path" => self::validateUrlsInsert($postActor->foto_path),
                        "popularidade" => $postActor->popularidade
                    ];

                    if(count($dados) > 0){  

                        $numInsercoes++;

                        Actors::cadastrar($dados);

                        Admin::cadastrarInsercao();

                    }

                }

                self::verifyNumInsertions(count($postActor), $numInsercoes);

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao inserir ator: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }

    
    /**
     * Método responsável por atualizar o registro de atores
     */
    public static function updateActor()
    {

        try{

            $dados = [];

            $colunas = [
                "id", 
                "nome", 
                "data_nascimento",
                "foto_path", 
                "genero", 
                "popularidade", 
                "nacionalidade"
            ];

            foreach($_POST as $key=>$value){
                
                if(in_array($key, $colunas)){

                    $dados[$key] = self::validateInputsInsert($value);

                }

            }

            if(count($dados) > 0){

                Actors::atualizar($_POST["updateActor"], $dados);
                
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