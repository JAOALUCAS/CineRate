<?php

namespace App\http;

use \App\http\Request;
use \App\http\Response;
use \App\controllers\pages\Manutenance;
use \App\controllers\Erro;

class Router{

    /**
     * Array de rotas
     *
     * @var array
     */
    private $routes = [];

    /**
     * Método responsável por organizar o array
     *
     * @param string $method
     * @param string $path
     * @param string $handler
     */
    public function add($method, $path, $handler) {

        $path = strtolower($path);

        $patternVariable = "/{(.*?)}/";

        $vars = [];

        if(preg_match_all($patternVariable, $path, $matches)){

            $path = preg_replace($patternVariable, "(.*?)", $path);

            $vars["variables"] = $matches[1];

        }

        $this->routes[] = [
            "method" => strtoupper($method), 
            "path" => $path,                 
            "handler" => $handler,
            "vars" => $vars
        ];

    }

    /**
     * Método responsável por verificar o array de rotas e chamar a respomse
     *
     * @param Request $request
     * @return string
     */
    public function run($request)
    {

        $uri  =  $request->getUri();

        $params = explode("/", $uri);

        $pr1 = "";

        $cleanUri = substr($uri, 1);

        unset($params[0]);

        unset($params[1]);

        $args = [];

        $rotinha = null;

        foreach($this->routes as $route){

            $rotinha = $route;

            if(isset($route["vars"]["variables"])){
                    
                $pr1 = $route["vars"]["variables"][0];

            }
            
            $patternRouteDinamic = "/^" . ucfirst($pr1) . "s\/([^\/]+)\/?(.*)$/";

            if ($route["method"] === $request->getMethod() && $route["path"] === $request->getUri() || preg_match($patternRouteDinamic, $cleanUri)){

                if(isset($route["vars"]["variables"])){

                    $key = array_values($route["vars"]["variables"]);

                    $args = array_combine($key, $params);

                }

                if(is_callable($route["handler"])) {

                    call_user_func($route["handler"], $request, $args);

                }

                if(is_array($route["handler"])){

                    $instance =  new $route["handler"][0];

                    $function = $route["handler"][1];
                    
                    $route["handler"] = call_user_func([$instance, $function], $request, $args);

                }

                $envValue = getenv("MANUTENANCE");
                
                $booleanValue = filter_var($envValue, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

                if($booleanValue && !str_contains($uri, "admin")){

                    return (new Response(200, Manutenance::manutenanceGetPage()))->sendResponse();

                }
                
                return (new Response(200, $route["handler"]))->sendResponse();

            }

        }

        return (new Response(404, ""))->sendResponse();

    }

    /**
     * Método responsável por rederecionar a url
     */ 
    public function redirect($route)
    {

        $url = URL.$route;

        header("location: ".$url);
        exit;

    }

}