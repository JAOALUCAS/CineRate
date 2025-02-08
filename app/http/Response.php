<?php

namespace App\http;

class Response{

    /**
     * Código de status http
     *
     * @var integer
     */
    private $httpCode;

    /**
     * Conteúdo retornado
     *
     * @var mixed
     */
    private $content;

    /**
     * Tipo de conteúdo retornado
     *
     * @var string
     */
    private $contentType;

    /**
     * Cabeçalho da response
     *
     * @var array
     */
    private $headers = [];

    /**
     * Método responsável por iniciar a classe e definir oa valores
     *
     * @param integer $httpCode
     * @param string $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = "text/html")
    {
        
        $this->httpCode = $httpCode;

        $this->content = $content;

        $this->setContentType($contentType);

    }

    /**
     * Método responsável por definir o contentType e chamar o addHeaders
     *
     * @param string $contentType
     */
    private function setContentType($contentType)
    {

        $this->contentType = $contentType;

        $this->addHeaders("Content-Type" ,$contentType);

    }

    /**
     * Método responsável por enviar um registro ao cabeçalho
     *
     * @param string $key
     * @param string $value
     */
    private function addHeaders($key, $value)
    {

        $this->headers[$key] = $value;
        
    }

    /**
     * Método responsável por enviar os headers
     */
    private function sendHeaders()
    {

        http_response_code($this->httpCode);

        foreach($this->headers as $key=>$value){

            header("$key: $value");

        }

    }

    /**
     * Método responsável por enviar a resposta
     */
    public function sendResponse()
    {

        $this->sendHeaders();

        switch($this->contentType){
            case "text/html":
                echo $this->content;
                break;
        }

    }

}