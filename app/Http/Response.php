<?php

namespace App\Http;

class Response {

    /**
     * Status httpCode
     * @var interger
     */
    private $httpCode = 200;

    /**
     * Headers
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do response
     * @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     * @var interger
     * @var mixed
     * @var string
     */
    public function __construct($httpCode, $content, $contentType = 'text/html') {
        $this->httpCode = $httpCode;
        $this->content = $content; 
        $this->setContentType($contentType);   
    }

        /**
     * Método responsável por alterar o contentType do Response
     * @var string
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type',$contentType);
    }

    /**
     * Método responsavel por adicionar um registro no cabeçalho de resposta
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = [$value];
    }

}