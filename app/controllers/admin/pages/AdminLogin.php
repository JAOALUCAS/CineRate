<?php

namespace App\controllers\admin\pages;

use \App\Utils\View;
use \App\database\Database;
use \App\controllers\Alert;
use \App\models\entity\Actors;
use \App\models\entity\User;
use \App\models\entity\Visits;
use \App\models\entity\Admin;
use \App\models\entity\Films;

class AdminLogin extends Page{

    public static $request;

    private static $userInfos = [];

    /**
     * Método responsáve por direcionar o post para o método correto
     */
    public static function directPost()
    {

        $postVars = self::$request->getPostVars();

        if(isset($postVars["adminSenha"])){

            return self::setLogin();

        }elseif(isset($postVars["postFilm"]) || isset($postVars["postActor"])){

            return self::insertDbMan();

        }

    }

    /**
     * Método responsável por lidar com o login de admin
     */
    private static function setLogin()
    {

        $postVars = self::$request->getPostVars();

        $adminSenha = $postVars["adminSenha"];        

        if(strlen($adminSenha) < 8){
         
            return self::loginGetPage("A senha deve conter no mínimo 8 caracteres para garantir maior segurança.");

        }
        if (!isset(self::$userInfos["senha"]) || !password_verify($adminSenha, self::$userInfos["senha"])) {

            return self::loginGetPage("Senha incorreta.");

        }

        $_SESSION["admin_id"] = self::$userInfos["id"];

        header("Location: /admin");
        exit();

    }

    /**
     * Método responsável por direcionar o usuário admin
     */
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

    /**
     * Verifica se a lista de filmes já está no banco de dados
     */
    private static function verifyFilmDuplicated($jsonsFilm)
    {

        $valores = [];

        foreach($jsonsFilm as $film){

            if($film){
                
                array_push($valores, $film->title);

            }

        }

        $titlesFormatedArray = array_map(fn($valor)=>
            $valor = "'". str_replace(['"', "'"], '', $valor) ."'"
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

    /**
     * Método responsável por inserir os filmes no banco de dados
     */
    private static function insertMovie($jsonsFilm)
    {

        if($jsonsFilm){

            $dados = [];

            $films = self::verifyFilmDuplicated($jsonsFilm);

            foreach($films as $json){

                if($json){
                    
                    $dados = [
                        "titulo" => str_replace(['"', "'"], '', $json->title),
                        "adulto" => isset($json->adult) ? (int) filter_var($json->adult, FILTER_VALIDATE_BOOLEAN) : null,
                        "ano_lancamento" => isset($json->release_date) ? date("Y", strtotime($json->release_date)) : null,
                        "data_lancamento" => isset($json->release_date) ? $json->release_date : null,
                        "descricao" => isset($json->overview) ? str_replace(['"', "'"], '', $json->overview) : null,
                        "tagline" => isset($json->tagline) ? str_replace(['"', "'"], '', $json->tagline) : null,
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
                        
                        Admin::cadastrarInsercao();

                    }

                }

            }

            return false;

        }

        return true;

    }

    /**
     * Verifica se a lista de atores já está no banco de dados
     */
    private static function verifyActorDuplicated($jsonsActor)
    {

        $valores = [];

        foreach($jsonsActor as $actor){

            array_push($valores, $actor->name);

        }
        
        $namesFormatedArray = array_map(fn($valor)=>
            $valor = "'". $valor ."'"
        , $valores);

        $namesFormatedString = implode(", ", $namesFormatedArray);

        $verifyDuplicated = Actors::verificarNomesDuplicados($namesFormatedString);
     
        if(count($verifyDuplicated) > 0){

            foreach($jsonsActor as $actor){

                foreach($verifyDuplicated as $index=>$duplicated){

                    if($actor->name == $duplicated["nome"]){
                        
                        $jsonsActor = array_splice($jsonsActor, $index, $index);

                    }

                }

            }

            return $jsonsActor;

        }

        return $jsonsActor;

    }
    
    /**
     * Método responsável por inserir os atores no banco de dados
     */
    private static function insertActor($jsonsActor)
    {
        
        if($jsonsActor){

            $dados = [];

            $actors = self::verifyActorDuplicated($jsonsActor);

            foreach($actors as $json){

                if($json){
                    
                    $dados = [
                        "nome" => $json->name,
                        "popularidade" => isset($json->popularity) ? $json->popularity : null,
                        "genero" => isset($json->gender) ? ($json->gender == 1 ? "feminino" : "masculino") : null,
                        "foto_path" => isset($json->profile_path) ? $json->profile_path : null,
                    ];

                    if(count($dados) > 0){
                        
                        Actors::cadastrar($dados);
                        
                        Admin::cadastrarInsercao();

                    }

                }

            }
            
            return false;

        }

        return true;

    }

    /**
     * Método responsável por direcionar os dados json para o método correto
     */
    private static function insertDbApi()
    {

        $getParams = self::$request->getQueryParams();

        foreach($getParams as $get){

            if(trim($get) !== "" && strlen($get) > 0){
                        
                if(!isset($_SESSION["admin_id"])){

                    die();

                }

            }

        }

        if(isset($getParams["jsonFilm"]) || isset($getParams["jsonActor"])){
        
            $jsonsFilm = isset($getParams["jsonFilm"]) ?  json_decode($getParams["jsonFilm"]) : null;

            $jsonsActor = isset($getParams["jsonActor"]) ? json_decode($getParams["jsonActor"]) :  null;

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
     * Método responsável por verificar se a inserção de post do ator já existe
     */
    private static function verifyActorDuplicatedMan($postActor)
    {

        $nomeFormatedString = "'".$postActor->nome."'";
        
        $verifyDuplicated = Actors::verificarNomesDuplicados($nomeFormatedString);
     
        if(count($verifyDuplicated) > 0){

            $postActor = null;

        }

        return $postActor;

    }

    /**
     * Método responsável por inserir o ator via post
     */
    private static function insertActorMan($postActor)
    {

        if($postActor){

            $dados = [];

            $verifyDuplicated = self::verifyActorDuplicatedMan($postActor);

            if($verifyDuplicated){

                $dados = [
                    "nome" => $postActor->nome,
                    "data_nascimento" => $postActor->datanascimento,
                    "nacionalidade" => $postActor->pais,
                    "genero" => $postActor->genero,
                    "foto_path" => $postActor->foto_path,
                    "popularidade" => $postActor->popularidade
                ];
                
                if(count($dados) > 0){
                        
                    Actors::cadastrar($dados);

                    Admin::cadastrarInsercao();

                }

            }

            return false;

        }

        return true;

    }

    /**
     * Método responsável por verificar se o post de film já existe no banco de dados
     */
    private static function verifyFilmDuplicatedMan($postFilm)
    {

        $nomeFormatedString = "'".$postFilm->titulo."'";
        
        $verifyDuplicated = Films::verificarFilmesDuplicados($nomeFormatedString);
     
        if(count($verifyDuplicated) > 0){

            $postFilm = null;

        }

        return $postFilm;

    }
    
    /**
     * Método responsável por inserir o filme via post
     */
    private static function insertMovieMan($postFilm)
    {

        if($postFilm){

            $dados = [];

            $verifyDuplicated = self::verifyFilmDuplicatedMan($postFilm);
            
            if($verifyDuplicated){

                $dados = [
                    "titulo" => $postFilm->titulo,
                    "tagline" => $postFilm->tagline,
                    "descricao" => $postFilm->descricao,
                    "data_lancamento" => $postFilm->datalancamento,
                    "ano_lancamento" => preg_split("/-/", $postFilm->datalancamento)[0],
                    "duracao" => $postFilm->duracao,
                    "elenco" => $postFilm->elenco,
                    "orcamento" => $postFilm->orcamento,
                    "poster" => $postFilm->poster,
                    "trailer" => $postFilm->trailer,
                    "streaming" => $postFilm->streaming,
                    "generos" => $postFilm->generos,
                    "diretor" => $postFilm->diretor,
                    "empresa" => $postFilm->empresa,
                    "pais" => $postFilm->pais
                ];
                
                if(count($dados) > 0){
                        
                    Films::cadastrar($dados);
                    
                    Admin::cadastrarInsercao();

                }

            }

            return false;

        }

        return true;

    }

    /**
     * Método responsável por gerenciar os posts de filme e atores
     */
    private static function insertDbMan()
    {
     
        $postFilm = isset($_POST["postFilm"]) ?  json_decode($_POST["postFilm"]) : null;
        $postActor = isset($_POST["postActor"]) ? json_decode($_POST["postActor"]) :  null;

        $erroInsert = false;

        if($postFilm){

            $erroInsert = self::insertMovieMan($postFilm);

        }else if($postActor){

            $erroInsert = self::insertActorMan($postActor);

        }

        if ($erroInsert) {

            $_SESSION["api_error"] = "Erro ao inserir dados.";

        }else{
            
            $_SESSION["api_sucess"] = "Sucesso ao inserir dados";

        }      
        
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;

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

        $statusApi = !is_null($errorMessageApi) ? Alert::getError($errorMessageApi) : (!is_null($sucessMessageApi) ? Alert::getSucess($sucessMessageApi) : "");

        print_r($statusApi);

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

        self::insertDbApi();

        return parent::callRenderPage("template", "Cinerate - Panel Login", $pageContent);

    }

}