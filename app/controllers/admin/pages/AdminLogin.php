<?php

namespace App\controllers\admin\pages;

use \App\Utils\View;
use \App\database\Database;
use \App\controllers\Alert;
use \App\models\entity\User;
use \App\models\entity\Visits;
use \App\models\entity\Admin;
use \App\models\entity\Films;

class AdminLogin extends Page{

    public static $request;

    private static $userInfos = [];

    public static function setLogin()
    {

        $postVars = self::$request->getPostVars();

        $adminSenha = $postVars["adminSenha"];        

        if(strlen($adminSenha) < 8){
         
            return self::loginGetPage("A senha deve conter no mínimo 8 caracteres para garantir maior segurança.");

        }

        if(!password_verify($adminSenha, self::$userInfos["senha"])){

            return self::loginGetPage("Senha incorreta.");

        }

        $_SESSION["admin_id"] = self::$userInfos["id"];

        header("Location: /admin");
        exit();

    }

    private static function verifyAdmin()
    {

        $uri = str_contains(self::$request->getUri(), "/admin") ? true : false;

        if(!isset($_SESSION["id"]) && $uri){

            die();

        }   

        $userVerify = Admin::getAdmins();

        foreach($userVerify as $user){
                
            if(!isset($user["id"]) && $uri){
                
                die();    

            }

        }

        self::$userInfos = $userVerify[0];

        if(isset($_SESSION["admin_id"])){

            return "panelContent";

        }

        return "loginContent";

    }


    private static function verifyFilmDuplicated($jsonsFilm)
    {

        print_r($jsonsFilm);

        $valores = [];

        foreach($jsonsFilm as $film){

            if($film){
                
                array_push($valores, $film->title);

            }

        }

        $titlesFormatedArray = array_map(fn($valor)=>
            $valor = "'". $valor ."'"
        , $valores);

        $titlesFormatedString = implode(", ", $titlesFormatedArray);

        $verifyDuplicated = Films::verificarFilmesDuplicados($titlesFormatedString);

        if(count($verifyDuplicated) > 0){

            foreach($jsonsFilm as $film){

                foreach($verifyDuplicated as $index=>$duplicated){

                    if($film->title == $duplicated["titulo"]){

                        $jsonsFilm = array_splice($jsonsFilm, $index, $index);

                    }

                }

            }

            return $jsonsFilm;

        }

        return $jsonsFilm;

    }

    private static function insertMovie($jsonsFilm)
    {

        if($jsonsFilm){

            $dados = [];

            $films = self::verifyFilmDuplicated($jsonsFilm);

            foreach($films as $json){

                if($json){
                    
                    $dados = [
                        "titulo" => $json->title,
                        "adulto" => isset($json->adult) ? (int) filter_var($json->adult, FILTER_VALIDATE_BOOLEAN) : null,
                        "ano_lancamento" => isset($json->release_date) ? date("Y", strtotime($json->release_date)) : null,
                        "data_lancamento" => isset($json->release_date) ? $json->release_date : null,
                        "descricao" => isset($json->overview) ? $json->overview : null,
                        "tagline" => isset($json->tagline) ? $json->tagline : null,
                        "duracao" => isset($json->runtime) ? $json->runtime : null,
                        "nota_generica" => isset($json->vote_average) ? $json->vote_average : null,
                        "votos_generico" => isset($json->vote_count) ? $json->vote_count : null,
                        "generos" => isset($json->genres) ? implode(", ", array_column($json->genres, "name")) : null,
                        "elenco" => isset($json->cast) ? $json->cast : null,
                        "diretores" => isset($json->crew) ? $json->crew : null,
                        "empresas" => isset($json->production_companies) ? implode(", ", array_column($json->production_companies, "name")) : null,
                        "paises" => isset($json->production_countries) ? implode(", ", array_column($json->production_countries, "name")) : null,
                        "orcamento" => isset($json->budget) ? $json->budget : null,
                        "bilheteria" => isset($json->revenue) ? $json->revenue : null,
                        "poster_url" => isset($json->poster_path) ? $json->poster_path : null,
                        "trailer_url" => isset($json->trailer_url) ? $json->trailer_url : null,
                        "streaming_disponivel" => isset($json->homepage) ? $json->homepage : null
                    ];

                    if(count($dados) > 0){
                        
                        Films::cadastrar($dados);

                    }

                }

            }

            return false;

        }

        return true;

    }

    
    private static function insertActor($jsonsActor)
    {
        
    }

    public static function insertDb()
    {

        if(isset($_GET["jsonFilm"]) || isset($_GET["jsonActor"])){
        
            $jsonsFilm = isset($_GET["jsonFilm"]) ?  json_decode($_GET["jsonFilm"]) : null;
            $jsonsActor = isset($_GET["jsonActor"]) ? json_decode($_GET["jsonActor"]) :  null;

            $erroInsert = false;

            if($jsonsFilm){

                $erroInsert = self::insertMovie($jsonsFilm);

            }else if($jsonsActor){

                $erroInsert = self::insertActor($jsonsActor);

            }

            if ($erroInsert) {

                $_SESSION["api_error"] = "Erro ao inserir dados.";

            }else{
                
                $_SESSION["api_sucess"] = "Sucesso ao inserir dados";

            }      
            
            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
            exit;

        }

    }

    /**
     * Método responsável por definir as visitas
     */
    private static function getVisits()
    {

        $visits = Visits::getVisits();

        return (string)$visits[0]["COUNT(*)"];

    }

    /**
     * Método responsável por definir visitas unicas
     */
    private static function getUniqueVisits()
    {

        $uniqueVisits = Visits::getVisits();

        return (string)$uniqueVisits[0]["COUNT(*)"];

    }

    
    /**
     * Método responsável por definir os novos usuarios
     */
    private static function getNewUsers()
    {

        $novosUsuarios = User::getUsers("data_criacao >= DATE_SUB(NOW(), INTERVAL 1 MONTH)", null, null, "COUNT(*)");
        
        return (string)$novosUsuarios[0]["COUNT(*)"];

    }

    /**
     * Método responsável por retornar as páginas de admin
     */
    public static function loginGetPage($errorMessage = null)
    {

        $view = "";

        $statusApi =

        $errorMessageApi = $_SESSION["api_error"] ?? null;

        $sucessMessageApi = $_SESSION["api_sucess"] ?? null;

        unset($_SESSION["api_error"]);
        unset($_SESSION["api_sucess"]);

        if(str_contains("/admin", self::$request->getUri())){
            
            $view = self::verifyAdmin();

        }

        $viewName = "pages/admin/" . $view;

        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : "";


        $statusApi = $status = !is_null($errorMessageApi) ? Alert::getError($errorMessageApi) : (!is_null($sucessMessageApi) ? Alert::getSucess($sucessMessageApi) : "");

        $pageContent = "";

        if($view == "panelContent"){
                
            $pageContent = View::renderPage($viewName, [
                "visits" => self::getVisits(),
                "uniqueVisits" => self::getUniqueVisits(),
                "newUsers"=> self::getNewUsers(),
                "status" => $statusApi
            ]);

        }elseif($view == "loginContent"){
                
            $pageContent = View::renderPage($viewName, [
                "email" => isset($_SESSION["email"]) ? $_SESSION["email"] : "",
                "status" => $status
            ]);

        }

        self::insertDb();

        return parent::callRenderPage("template", "Cinerate - Panel Login", $pageContent);

    }

}