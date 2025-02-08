<?php

namespace App\http;

use \App\http\Request;
use \App\http\Response;

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

        $this->routes[] = [
            "method" => strtoupper($method), 
            "path" => $path,                 
            "handler" => $handler            
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

        foreach($this->routes as $route){

            if ($route["method"] === $request->getMethod() && $route["path"] === $request->getUri()){

                if(is_callable($route["handler"])) {

                    call_user_func($route["handler"], $request);

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