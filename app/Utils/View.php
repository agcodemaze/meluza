<?php
namespace App\Utils;

class View {

    /**
     * Retorna o conteúdo da view processando variáveis PHP.
     *
     * @param string $view Nome da view (sem extensão)
     * @param array $vars Variáveis a serem passadas para a view
     * @return string
     * @throws \Exception Se a view não existir
     */
    public static function render(string $view, array $vars = []): string {
        $file = __DIR__ . '/../../resources/view/' . $view . '.php';

        if (!file_exists($file)) {
            throw new \Exception("View '{$view}' não encontrada em {$file}");
        }

        // Extrai as variáveis para serem acessíveis como $variavel
        extract($vars);

        // Inicia buffer de saída
        ob_start();

        // Inclui a view (agora com acesso às variáveis)
        include $file;

        // Retorna o conteúdo do buffer
        return ob_get_clean();
    }

    function conexao()
    {
        
        $host = $_ENV['ENV_BD_HOST'];
        $dbname = "1000"; 
        $user = $_ENV['ENV_BD_USER'];
        $pass = $_ENV['ENV_BD_PASS'];

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        } 
    }



}
