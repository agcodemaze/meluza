<?php 

namespace App\Model\Entity;
use PDO;
use PDOException;

/**
 * A classe Conn é responsável por gerenciar a conexão com o banco de dados.
 *
 * Ela encapsula a lógica de conexão e fornece uma instância de PDO
 * para que outras classes possam interagir com o banco de dados.
 */
class Conn {

    /**
     * @var PDO A instância de PDO para a conexão com o banco de dados.
     */
    public $pdo;

    /**
     * Construtor da classe Conn.
     *
     * Inicializa automaticamente a conexão com o banco de dados
     * assim que um objeto Conn é instanciado.
     */
    public function __construct() {
        $this->conexao();
    }

    /**
     * Inicializa a conexão com o banco de dados.
     *
     * Cria uma nova instância de PDO usando as credenciais do ambiente
     * e a armazena na propriedade $this->pdo. Em caso de falha na conexão,
     * o script é encerrado.
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