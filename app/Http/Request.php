<?php

namespace App\Http;

class Request {

    /**
     * Método Http da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis que vão ser recebido no POST da página
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da Requisição
     * @var array
     */
    private $headers = [];

    /**
     * Construtor da Classe
     */
    public function __construct() {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders(); //função do php para pegar os headers
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * Método responsável por retornar o método http da requisição
     * @var string
     */
    public function getHttpMethod() {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar a URI da requisição
     * @var string
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * Método responsável por retornar o header da requisição
     * @var array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Método responsável por retornar os parametros da url da  da requisição
     * @var array
     */
    public function getQueryParams() {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar as variaveis POST da requisição
     * @var array
     */
    public function getPostVars() {
        return $this->postVars;
    }



}