<?php

namespace App\controllers\pages;

use \App\controllers\Alert;
use \App\Utils\View;
use \App\models\entity\User;
use \App\session\Session;
use \App\communication\Email;
use \Exception;

class Auth extends Page{

    public static $request;

    private static $verifyEmailCode;

    private static $userInfos = [];

    /**
     * Método responsável por encaminhar entre o login e o registro, já que o post vem da mesma página
     */
    public static function decideAuth()
    {

        $postVars = self::$request->getPostVars();

        $instancia = new self();

        if(isset($postVars["nome"])){
            
            return $instancia->setNewUser($postVars);

        }else{
            
            return $instancia->setLogin($postVars);

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
     */
    private function setLogin($postVars)
    {
        try {

            $paramsValidated = $this->validateInputsLogin($postVars);
            
            $email = $paramsValidated["email"] ?? "";
            $senha = $paramsValidated["senha"] ?? "";
            
            $obUser = new User;
            $obUser->email = $email;

            $userVerify = $obUser->getUserByEmail();

            if (!$userVerify || !password_verify($senha, $userVerify[0]["senha"])) {

                return self::authGetPage("authContent", "
                    A senha informada está incorreta. Verifique suas credenciais e tente novamente.");

            }

            Session::setVars([
                "id" => $userVerify[0]["id"],
                "nome" => $userVerify[0]["nome"],
                "email" => $userVerify[0]["email"]
            ]);

            $_SESSION["login_msg"] = true;

            return self::$request->getRouter()->redirect("");

        } catch (Exception $e) {
        
            return self::$request->getRouter()->redirect("/Account");

        }

    }

    /**
     * Método responsável por validar os inputs de registro
     *
     * @param array $postVars
     * @return array
     */
    private function validateInputsRegister($postVars)
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

        if($postVars["confirmarSenha"] !== $senha){

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
     */
    private function setNewUser($postVars)
    {
        try {

            $paramsValidated = $this->validateInputsRegister($postVars);

            $email = $paramsValidated["email"] ?? "";
            $nome = $paramsValidated["nome"] ?? "";
            $senha = $paramsValidated["senha"] ?? "";
            
            $obUser = new User;
            $obUser->email = $email;

            $verifyDuplicateEmail = $obUser->getUserByEmail();

            if (!empty($verifyDuplicateEmail)) {

                return self::authGetPage("authContent", "
                    O e-mail informado já está cadastrado em nosso sistema. Utilize outro e-mail ou recupere sua conta.");

            }

            self::$verifyEmailCode = isset($_SESSION["emailCode"]) ? $_SESSION["emailCode"] : rand(100000, 999999);

            $_SESSION["emailCode"] = self::$verifyEmailCode;

            self::$userInfos = [
                "nome" => $nome,
                "email" => $email,
                "senha" => $senha
            ];

            $_SESSION["userInfos"] = self::$userInfos;

            return $this->sendEmailVerify($email, "register", self::$verifyEmailCode);

        } catch (Exception $e) {
            
            return self::$request->getRouter()->redirect("/Account");

        }

    }


    /**
     * Método responsável por enviar emails
     *
     * @param string $address
     * @param string $action
     */
    private function sendEmailVerify($address, $action, $verifyEmailCode = null)
    {

        $subject = "";
        
        $nome = self::$userInfos["nome"] ?? "Usuário";

        switch ($action) {
            case 'register':
                $subject = "Olá, $nome!\n\nSeu registro foi realizado com sucesso. Para ativar sua conta, utilize o seguinte código de verificação:\n\nCódigo: $verifyEmailCode\n\nSe você não realizou esse cadastro, ignore este e-mail.";
                break;
            case 'delete':
                $subject = "Olá, $nome!\n\nSua conta foi excluída permanentemente. Se isso foi um engano e você deseja recuperá-la, entre em contato com nosso suporte o mais rápido possível.";
                break;
            case 'update':
                $subject = "Olá, $nome!\n\nAs informações da sua conta foram atualizadas com sucesso. Se você não reconhece essa alteração, por favor, entre em contato com nosso suporte.";
                break;
            default:
                $subject = "Olá, $nome!\n\nRecebemos uma solicitação desconhecida para sua conta. Se não foi você, por favor, ignore este e-mail.";
                break;
        }
    
        $body = View::renderPage("pages/email/emailContent", [
            "nome" => $nome, 
            "mensagem" => $subject,
            "codigo" => $verifyEmailCode
        ]);
        
        // Enviar o email
        $obMail = new Email;

        $obMail->FROM_EMAIL = $address;

        $obMail->FROM_NAME = $nome;

        $sucess = $obMail->sendEmail($address, "Cinerate", $body);

        if($sucess){

            return self::$request->getRouter()->redirect("/Account/Code");

        }

        return self::authGetPage("authContent", "
                Não foi possivel enviar o código de verificação para o email digitado. Por favor tente novamente");

    }

    /**
     * Método responsável por cadastrar novo usuário se o código digitado estiver correto
     */
    public static function codeVerify()
    {
        try {

            $postVars = self::$request->getPostVars();
            $code = "";     

            if (isset($postVars)) {

                foreach ($postVars as $key => $value) {

                    if (str_contains($key, "digit")) {

                        $code .= $value;

                    }

                }

                if (strlen($code) == 6 && (string)$code == (string)self::$verifyEmailCode) {

                    self::$userInfos = $_SESSION["userInfos"];

                    $nome = self::$userInfos["nome"];

                    $email = self::$userInfos["email"];

                    $senha = self::$userInfos["senha"];

                    $obUser = new User;

                    $obUser->nome = $nome;

                    $obUser->email = $email;

                    $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);

                    $obUser->cadastrar();

                    Session::setVars([
                        "id" => $_SESSION["last_id"],
                        "nome" => self::$userInfos["nome"],
                        "email" => self::$userInfos["email"]
                    ]);

                    $_SESSION["register_msg"] = true;

                    return self::$request->getRouter()->redirect("");

                }

            }

            unset($_SESSION["emailCode"]);

            return self::authGetPage("authContent", "
                Não foi possível obter o código digitado. Por favor, tente novamente.");

        } catch (Exception $e) {

            
            return self::$request->getRouter()->redirect("/Account");

        }
        
    }


    /**
     * Método responsável por retornar a página de auth
     *
     * @param string $view
     * @param string $errorMessage
     */
    public static function authGetPage($view, $errorMessage = null)
    {

        self::$verifyEmailCode = isset($_SESSION["emailCode"]) ? $_SESSION["emailCode"] : null;

        $url = self::$request->getUri();

        if(str_contains($url, "code")){

            if(self::$verifyEmailCode == null){

                die();    

            }

        }

        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : "";

        $viewName = "pages/" . $view;

        $pageContent = View::renderPage($viewName, [
            "status" => $status
        ]);

        return parent::callRenderPage("template", "Cinerate - registrar", $pageContent, false, false);

    }

}