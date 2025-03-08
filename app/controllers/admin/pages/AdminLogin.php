<?php

namespace App\controllers\admin\pages;

use \App\Utils\View;
use \App\controllers\Alert;
use \App\controllers\Manutenance;
use \App\models\entity\User;
use \App\models\entity\Admin;
use App\controllers\admin\ActorsControllers;
use \App\controllers\admin\FilmsControllers;
use \App\controllers\admin\InsertsController;
use \App\controllers\admin\DashboardController;
use \Exception;

class AdminLogin extends Page{

    /**
     * Request
     *
     * @var Request
     */
    public static $request;

    /**
     * Informações do usuário
     *
     * @var array
     */
    private static $userInfos = [];

    /**
     * Método responsáve por direcionar o post para o método correto
     */
    public static function directPost()
    {

        $postVars = self::$request->getPostVars();

        switch (true) {
            case isset($postVars["adminSenha"]):
                return self::setLogin();
        
            case isset($postVars["postFilm"]) || isset($postVars["postActor"]):
                return self::insertDbMan();
        
            case isset($postVars["newAdmin"]):
                return self::setNewAdmin();
        
            case isset($postVars["deleteAdmin"]):
                return self::deleteAdmin();

            case isset($postVars["manutenance"]):
                return self::defineManutenance();

            case isset($postVars["filterInsert"]):
                return self::getInsercoes();

            case isset($postVars["updateFilm"]):
                return self::updateFilm();

            case isset($postVars["updateActor"]):
                return self::updateActor();

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

        try {

            $uri = str_contains(self::$request->getUri(), "/admin") ? true : false;

            if (!isset($_SESSION["id"]) && $uri) {

                die();

            }

            $userVerify = Admin::getAdmins();

            foreach ($userVerify as $user) {

                if (!isset($user["id"]) && $uri) {

                    die();

                }

            }

            self::$userInfos = $userVerify[0];

            if (isset($_SESSION["admin_id"])) {

                return "panelContent";

            }

            return "loginContent";

        } catch (Exception $e) {
    
            $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

            header("Location: /admin");

            exit();

        }

    }

    /**
     * Método responsável por inserir os filmes no banco de dados
     */
    private static function insertMovie($jsonsFilm)
    {

        if($jsonsFilm){
                
            $filmsError = FilmsControllers::insertMovie($jsonsFilm);

            if(!$filmsError){                

                return false;

            }

        }

        return true;

    }

    /**
     * Método responsável por inserir os atores no banco de dados
     */
    private static function insertActor($jsonsActor)
    {

        if($jsonsActor){

            $actorsError = ActorsControllers::insertActor($jsonsActor);

            if(!$actorsError){
                
                return false;

            }

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
     * Método responsável por inserir o ator via post
     */
    private static function insertActorMan($postActor)
    {

        if($postActor){
            
            $actorsError = ActorsControllers::insertActorMan($postActor);

            if(!$actorsError){
                
                return false;

            }

        }

        return true;

    }
    
    /**
     * Método responsável por inserir o filme via post
     */
    private static function insertMovieMan($postFilm)
    {

        if($postFilm){

            $filmsError = FilmsControllers::insertMovieMan($postFilm);
            
            if(!$filmsError){

                return false;

            }

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
     
        return DashboardController::getVisits();

    }

    /**
     * Método responsável por definir visitas unicas
     */
    private static function getUniqueVisits()
    {

        return DashboardController::getUniqueVisits();

    }

    /**
     * Método responsável por definir os novos usuarios
     */
    private static function getNewUsers()
    {
        
        return DashboardController::getNewUsers();

    }

    

    /**
     * Método responsável por retornar todos os admins do banco de dados
     */
    private static function getAdmins()
    {
        try {

            $admins = Admin::getAdmins();

            $users = "";

            foreach ($admins as $admin) {

                $obUser = new User;

                $obUser->id = $admin["usuario_id"];

                $infos = $obUser->getUserById();

                $users .= "<tr>
                                <td>{$admin["id"]}</td>
                                <td>{$infos[0]["nome"]}</td>
                                <td>{$infos[0]["email"]}</td>
                                <td class='acoes'>
                                    <form method='post' class='delete-admin'>
                                        <input type='hidden' name='idDelete' value='{$admin["id"]}'>
                                        <button class='btn-excluir'>Excluir</button>
                                    </form>
                                </td>
                            </tr>";
            }

            return $users;

        } catch (Exception $e) {
            
            $_SESSION["api_error"] = "Erro ao buscar administradores: " . $e->getMessage();

            return "";

        }

    }

    /**
     * Método responsável por atualizar o registro de filmes
     */
    private static function updateFilm()
    {

        return FilmsControllers::updateFilm();        

    }
    
    /**
     * Método responsável por atualizar o registro de atores
     */
    private static function updateActor()
    {

        return ActorsControllers::updateActor();

    }

    /**
     * Método responsável por retornar opções de busca por inserção
     */
    private static function getSelectsInsercoes()
    {

        return InsertsController::getSelectsInsercoes();
                
    }

    /**
     * Método responsável por retornar as inserções sem filtros
     */
    private static function getGenericInsercoes()
    {
        
        return InsertsController::getGenericInsercoes();

    }

    /**
     * Método responsável por retornar as inserções de acordo com o filtro especificado
     */
    private static function getInsercoes()
    {

        return InsertsController::getInsercoes();

    }

    

    /**
     * Método responsável por inserir um novo admin no banco
     */
    private static function setNewAdmin()
    {
        $postEmail = isset($_POST["newAdminEmail"]) ? $_POST["newAdminEmail"] : null;
        unset($_POST["newAdminEmail"]);

        if ($postEmail) {

            try {

                $obUser = new User;

                $obUser->email = $postEmail;

                $verifyExist = $obUser->getUserByEmail();

                if (count($verifyExist) > 0) {

                    Admin::$id = $verifyExist[0]["id"];

                    $verifyAdmin = Admin::getAdminById();

                    if (count($verifyAdmin) > 0) {

                        $_SESSION["api_error"] = "O usuário requisitado já é um administrador do site!";

                        header("Location: /admin");

                        exit();

                    }

                    $_SESSION["api_sucess"] = "Usuário inserido com sucesso!";

                    Admin::$senha = $verifyExist[0]["senha"];

                    Admin::cadastrar();

                    header("Location: /admin");

                    exit();

                }

                $_SESSION["api_error"] = "O usuário requisitado não existe!";

                header("Location: /admin");

                exit();


            } catch (Exception $e) {

                $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

                header("Location: /admin");

                exit();

            }

        }

    }

    /**
     * Método responsável por deletar um admin do banco de dados
     */
    private static function deleteAdmin()
    {

        $id = isset($_POST["idDelete"]) ? $_POST["idDelete"] : null;

        if ($id) {

            try {
    
                if ($_SESSION["admin_id"] == $id) {

                    $_SESSION["api_error"] = "Não é possivel excluir a si mesmo!";

                    header("Location: /admin");

                    exit();

                }

                Admin::$id = $id;

                Admin::excluir();

                $_SESSION["api_sucess"] = "Usuário excluído com sucesso!";

                header("Location: /admin");

                exit();

            } catch (Exception $e) {
                
                $_SESSION["api_error"] = "Erro na aplicação: " . $e->getMessage();

                header("Location: /admin");

                exit();

            }

        }

        $_SESSION["api_error"] = "Não apague o id do usuário!";

        header("Location: /admin");

        exit();

    }

    /**
     * Método responsável por definir a manutenção das páginas
     */
    private static function defineManutenance()
    {

        return Manutenance::defineStatus();

    }

    /**
     * Método responsável por retornar o botão de manutenção
     */
    private static function getManutenanceBtn()
    {

        return Manutenance::getManutenanceBtn();

    }

    /**
     * Método responsável por retornar a dashboard do panel
     */
    private static function getDashboardView()
    {

        return View::renderPage("pages/admin/dashboard", [
            "visits" => self::getVisits(),
            "uniqueVisits" => self::getUniqueVisits(),
            "newUsers"=> self::getNewUsers(),
            "btn-manutencao" => self::getManutenanceBtn()
        ]);

    }

    
    /**
     * Método responsável por retornar os usuários do panel
     */
    private static function getUsersView()
    {

        return View::renderPage("pages/admin/users", [
            "admins" => self::getAdmins()
        ]);

    }
    
    /**
     * Método responsável por retornar a guia de inserções do panel
     */
    private static function getInsertsView()
    {
        
        return View::getContentView("pages/admin/inserts");

    }
    
    /**
     * Método responsável por retornar uma visualização de conteúdo do site no panel
     */
    private static function getContentsView()
    {

        return View::renderPage("pages/admin/content", [
            "selects" => self::getSelectsInsercoes(),
            "relatorios" => isset($relatoriosEspecificos) ? $relatoriosEspecificos : self::getGenericInsercoes()
        ]);

    }

    /**
     * Método responsável por retornar as denúcias do panel
     */
    private static function getComplaintView()
    {

        return View::renderPage("pages/admin/complaint");

    }
    
    /**
     * Método responsável por retornar os formulários de ajuda do panel
     */
    private static function getHelpsView()
    {

        return View::renderPage("pages/admin/help");

    }

    /**
     * Método responsável por retornar as páginas de admin
     */
    public static function loginGetPage($errorMessage = null)
    {
        
        unset($_POST["deleteAdmin"]);

        $view = "";

        $statusApi =

        $errorMessageApi = $_SESSION["api_error"] ?? null;

        $sucessMessageApi = $_SESSION["api_sucess"] ?? null;

        $warningMessageApi = $_SESSION["warning_insercoes"] ?? null;

        unset($_SESSION["api_error"]);

        unset($_SESSION["api_sucess"]);

        unset($_SESSION["warning_insercoes"]);

        if(str_contains("/admin", self::$request->getUri())){
            
            $view = self::verifyAdmin();

        }

        $viewName = "pages/admin/" . $view;

        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : "";

        $statusApi = !is_null($errorMessageApi) ? Alert::getError($errorMessageApi) : (!is_null($sucessMessageApi) ? Alert::getSucess($sucessMessageApi) : (!is_null($warningMessageApi) ? Alert::getAlert($warningMessageApi) : ""));

        $pageContent = "";

        $relatoriosEspecificos = isset($_SESSION["especific_inserts"]) ? $_SESSION["especific_inserts"] : null;

        unset($_SESSION["especific_inserts"]);

        if($view == "panelContent"){
                
            $pageContent = View::renderPage($viewName, [
                "dashboard" => self::getDashboardView(),
                "users" => self::getUsersView(),
                "inserts" => self::getInsertsView(),
                "content" => self::getContentsView(),
                "complaint" => self::getComplaintView(),
                "help" => self::getHelpsView(),
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