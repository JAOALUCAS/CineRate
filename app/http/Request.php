<?php

namespace App\http;

class Request{

    /**
     * Uri
     *
     * @var string
     */
    private $uri;

    /**
     * Paramêtros get
     *
     * @var string
     */
    private $queryParams;

    /**
     * Váriaveis post
     *
     * @var string
     */
    private $postVars;

    /**
     * Método da requisição
     *
     * @var string
     */
    private $method;

    /**
     * Método responsável por iniciar a classe e definir as váriaveis
     */
    public function __construct()
    {

        $this->uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        $this->queryParams = $_GET;

        $this->method = $_SERVER["REQUEST_METHOD"];

        $this->postVars = $_POST;
        
    }

    /**
     * Método responsável por enviar a uri
     */
    public function getUri()
    {

        return $this->uri;

    }

    /**
     * Método responsável por retornar as váriaveis post
     */
    public function getPostVars()
    {

        return $this->postVars;

    }

    /**
     * Método retornar paramêtros get
     */
    public function getQueryParams()
    {

        return $this->queryParams;

    }

    /**
     * Método responsável por retornar os métodos
     */
    public function getMethod()
    {

        return $this->method;

    }

}