<?php

namespace App\controllers\pages;

use \App\controllers\Alert;
use \App\database\Database;
use \App\Utils\View;
use \App\models\entity\User;
use \App\session\Session;

class Auth extends Page{

    public static $request;

    /**
     * Método responsável por encaminhar entre o login e o registro, já que o post vem da mesma página
     *
     * @param Request $request
     */
    public static function decideAuth()
    {

        $postVars = self::$request->getPostVars();

        $instancia = new self();

        if(isset($postVars["nome"])){
            
            return $instancia->setNewUser($postVars, self::$request);

        }else{

            return $instancia->setLogin($postVars, self::$request);

        }

    }

    /**
     * Método responsável por validar os inputs de login
     *
     * @param array $postVars
     * @return array
     */
    private function validateInputsLogin($postVars)
    {

        $email = trim(filter_var($postVars["email"], FILTER_SANITIZE_EMAIL)); 

        $senha = trim($postVars["senha"]);
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            return self::authGetPage("authContent", "
                 O e-mail informado não é válido. Por favor, verifique o endereço digitado e tente novamente.");

        }
        
        $paramsValidated = [
            "email" => $email, 
            "senha" => $senha
        ];

        return $paramsValidated;

    }

    /**
     * Método responsável por fazer login
     *
     * @param array $postVars
     * @param Request $request
     */
    private function setLogin($postVars, $request)
    {

        $paramsValidated = $this->validateInputsLogin($postVars, $request);
           
        $email = $paramsValidated["email"] ?? "";

        $senha = $paramsValidated["senha"] ?? "";
           
        $obUser = new User;

        $obUser->email = $email;

        $userVerify = $obUser->getUserByEmail();

        if(!password_verify($senha, $userVerify[0]["senha"])){

            return self::authGetPage("authContent", "
                A senha informada está incorreta. Verifique suas credenciais e tente novamente.");

        }

        Session::setVars([
            "id" => $userVerify[0]["id"],
            "nome" => $userVerify[0]["nome"],
            "email" => $userVerify[0]["email"]
        ]);

        return self::$request->getRouter()->redirect("");

    }

    /**
     * Método responsável por validar os inputs de registro
     *
     * @param array $postVars
     * @return array
     */
    private function validateInputsRegister($postVars, $request)
    {

        $email = trim(filter_var($postVars["email"], FILTER_SANITIZE_EMAIL)); 

        $nome = trim(filter_var($postVars["nome"], FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $senha = trim($postVars["senha"]);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            return self::authGetPage("authContent", "
                O e-mail informado não é válido. Por favor, verifique o endereço digitado e tente novamente.");

        }

        if(strlen($nome) < 3){

            return self::authGetPage("authContent", "                               
                O nome deve conter pelo menos 3 caracteres. Por favor, insira um nome válido.");

        }

        if(strlen($senha) < 8){

            return self::authGetPage("authContent", "
                A senha deve conter no mínimo 8 caracteres para garantir maior segurança.");

        }

        if($postVars["confirmarSenha"] == $senha){

            return self::authGetPage("authContent", "
                As senhas digitadas não coincidem. Certifique-se de que ambas estão corretas e tente novamente.");

        }

        $paramsValidated = [
            "email" => $email, 
            "nome" => $nome, 
            "senha" => $senha
        ];

        return $paramsValidated;

    }

    /**
     * Método responsável por fazer o registro
     *
     * @param array $postVars
     * @param Request $request
     */
    private function setNewUser($postVars, $request)
    {

        $paramsValidated = $this->validateInputsRegister($postVars, $request);

        $email = $paramsValidated["email"] ?? "";

        $nome = $paramsValidated["nome"] ?? "";

        $senha = $paramsValidated["senha"] ?? "";
        
        $obUser = new User;

        $obUser->email = $email;

        $verifyDuplicateEmail = $obUser->getUserByEmail();

        if(!empty($verifyDuplicateEmail)){

            return self::authGetPage("authContent", "
                O e-mail informado já está cadastrado em nosso sistema. Utilize outro e-mail ou recupere sua conta.");

        }

        $obUser->nome = $nome;

        $obUser->email = $email;

        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);

        $userVerify = $obUser->cadastrar();
        
        Session::setVars([
            "id" => $userVerify[0]["id"],
            "nome" => $userVerify[0]["nome"],
            "email" => $userVerify[0]["email"]
        ]);

        return self::$request->getRouter()->redirect("");

    }


    public static function authGetPage($view, $errorMessage = null)
    {

        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : "";

        $viewName = "pages/" . $view;

        $pageContent = View::renderPage($viewName, [
            "status" => $status
        ]);

        return parent::callRenderPage("template", "Cinerate - registrar", $pageContent);

    }

}