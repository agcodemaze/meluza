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




}