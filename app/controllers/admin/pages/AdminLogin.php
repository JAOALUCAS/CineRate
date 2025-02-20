<?php

namespace App\controllers\admin\pages;

use \App\Utils\View;
use \App\database\Database;
use \App\controllers\Alert;
use \App\models\entity\User;
use \App\models\entity\Visits;
use \App\models\entity\Admin;

class AdminLogin extends Page{

    public static $request;

    private  static $userInfos = [];

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

        $obUser = new Database("admins");

        $userVerify = $obUser->select();

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

        if(str_contains("/admin", self::$request->getUri())){
            
            $view = self::verifyAdmin();

        }

        $viewName = "pages/admin/" . $view;

        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : "";

        $pageContent = "";

        if($view == "panelContent"){
                
            $pageContent = View::renderPage($viewName, [
                "visits" => self::getVisits(),
                "uniqueVisits" => self::getUniqueVisits(),
                "newUsers"=> self::getNewUsers()
            ]);

        }elseif($view == "loginContent"){
                
            $pageContent = View::renderPage($viewName, [
                "email" => isset($_SESSION["email"]) ? $_SESSION["email"] : "",
                "status" => $status
            ]);

        }

        return parent::callRenderPage("template", "Cinerate - Panel Login", $pageContent);

    }

}