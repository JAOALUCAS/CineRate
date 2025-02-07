<?php

namespace App\controllers\pages;

use App\controllers\Alert;
use \App\database\Database;
use \App\Utils\View;
use \App\models\entity\User;

class Auth extends Page{

    /**
     * Método responsável por encaminhar entre o login e o registro, já que o post vem da mesma página
     *
     * @param Request $request
     */
    public function decideAuth($request)
    {

        $postVars = $request->getPostVars();

        if(isset($postVars["nome"])){
            
            return $this->setNewUser($postVars, $request);

        }

        return $this->setLogin($postVars, $request);

    }

    /**
     * Método responsável por validar os inputs de login
     *
     * @param array $postVars
     * @return array
     */
    private function validateInputsLogin($postVars)
    {

        $errors = [];

        $email = trim(filter_var($postVars["email"], FILTER_SANITIZE_EMAIL)); 

        $senha = trim($postVars["senha"]);
        
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            $erros["email"] = "Digite um email válido";

        }
        
        if(count($errors) !== 0){

            return Alert::getError($errors);

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
        
        $paramsValidated = $this->validateInputsLogin($postVars);
           
        $email = $paramsValidated["email"] ?? "";

        $senha = $paramsValidated["senha"] ?? "";
           
        $obUser = new User;

        $userVerify = $obUser->getUserByEmail();

        if(!password_verify($senha, $userVerify["senha"])){

            return Alert::getError([
                "senha" => "Senha incorreta"
            ]);

        }

        Session::setVars($userVerify);

        return $request->getRouter()->redirect("/login?status=success");

    }

    /**
     * Método responsável por validar os inputs de registro
     *
     * @param array $postVars
     * @return array
     */
    private function validateInputsRegister($postVars)
    {

        $errors = [];

        $email = trim(filter_var($postVars["email"], FILTER_SANITIZE_EMAIL)); 

        $nome = trim(filter_var($postVars["nome"], FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $senha = trim($postVars["senha"]);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            $erros["email"] = "Digite um email válido";

        }

        if(strlen($nome) < 3){

            $errors["nome"] = "O nome precisa ter no minimo 3 caracters";

        }

        if(strlen($senha) < 8){

            $erros["senha"] = "A senha precisa ter no minimo 8 caracteres";

        }

        if(count($errors) !== 0){

            return Alert::getError($errors);

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

        $paramsValidated = $this->validateInputsRegister($postVars);
            
        $email = $paramsValidated["email"] ?? "";

        $nome = $paramsValidated["nome"] ?? "";

        $senha = $paramsValidated["senha"] ?? "";
        
        $obUser = new User;

 -      $verifyDuplicateEmail = $obUser->getUserByEmail($email);

        if($verifyDuplicateEmail instanceof User){

            return Alert::getError([
                "email" => "Email já cadastrado"
            ]);

        }

        $obUser->nome = $nome;

        $obUser->email = $email;

        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);

        $obUser->cadastrar();

    }

    public static function authGetPage($view)
    {

        $viewName = "pages/" . $view;

        $pageContent = View::getContentView($viewName);

        return parent::callRenderPage("template", "Cinerate - registrar", $pageContent, $view);

    }

}