<?php

namespace App\Http;
use \Closure;
use \Exception;

class Router {

    /**
     * Url completa do projeto
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instância de request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     */
    public function __construct($url) {
        $this->request  = new Request();
        $this->url      = $url;
        $this->setPrefix();
    }

    /**
     * Métoo responsável por definir o prefixo das rotas
     */
    private function setPrefix(){
        //INFORMAÇÕES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        //DEFINE PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params =[]){
        //VALIDAÇÃO DOS PARAMETROS
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';
        
        //ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;

    }

    /**
     * Método responsavel por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        return $this->addRoute('GET',$route, $params);
    }

    /**
     * Método responsável por retonrar a URI desconsiderando o prefixo.
     * @return string
     */
    private function getUri(){
        //URI DA REQUEST
        $uri = $this->request->getUri();

        //FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        return end($xUri);

    }

    /**
     * Retorna os parametros da rota atual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        //VALIDA AS ROTAS
        foreach($this->routes as $patternRoute=>$methods){
            //VERIFICA SE A URI BATE COM O PADRAO
            if(preg_match($patternRoute,$uri)){
                //VERIFICA O METHOD
                if($methods[$httpMethod]){
                    //RETORNO DOS PARAMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                throw new Exception('Método não permitido', 405);
            }
        }
        throw new Exception('Url não encontrada', 404);

    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run() {
        try{            
            //OBTEM A ROTA ATUAL
            $route = $this->getRoute();
            return new Response('Teste');

        } catch(Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }
    }

}