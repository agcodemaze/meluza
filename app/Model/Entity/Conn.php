<?php 

namespace App\Model\Entity;
use PDO;
use PDOException;

class Conn {

    public $pdo;

    /**
     * Construtor
     * Inicializa a conexão automaticamente ao instanciar a classe.
     */
    public function __construct()
    {
        $this->conexao();
    }

    /**
    * Inicializa a conexão com o banco de dados.
    *
    * Cria uma instância de PDO e armazena em $this->pdo.
    * Caso ocorra um erro na conexão, o script é encerrado com die().
    *
    * @return void
    */
    private function conexao() {

        $host   = $_ENV['ENV_BD_HOST'] ?? getenv('ENV_BD_HOST') ?? '';
        $dbname = $_ENV['ENV_BD_NAME'] ?? getenv('ENV_BD_NAME') ?? '';
        $user   = $_ENV['ENV_BD_USER'] ?? getenv('ENV_BD_USER') ?? '';
        $pass   = $_ENV['ENV_BD_PASS'] ?? getenv('ENV_BD_PASS') ?? '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        } 
    }

}