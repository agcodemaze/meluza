<?php
namespace App\Core;

/**
 * A classe Language gerencia a configuração de idioma da aplicação.
 *
 * Ela define os idiomas suportados, carrega o arquivo de tradução correto
 * e fornece um método para buscar strings traduzidas.
 */
class Language {
    private static $supported = ['pt', 'en', 'es'];
    private static $default = 'pt';
    private static $translations = [];

    /**
     * Inicializa o sistema de gerenciamento de idiomas.
     *
     * Este método verifica se um idioma foi especificado na URL,
     * define o idioma na sessão e carrega o arquivo de tradução correspondente.
     * Se o arquivo de idioma não for encontrado, ele carrega o idioma padrão.
     *
     * @return void
     */
    public static function init() {       

        if (isset($_GET['lang']) && in_array($_GET['lang'], self::$supported)) {
            $_SESSION['lang'] = $_GET['lang'];
        }

        $lang = $_SESSION['lang'] ?? self::$default;

        // Carrega arquivo do idioma
        $file = __DIR__ . "/../Lang/{$lang}.php";
        if (file_exists($file)) {
            self::$translations = require $file;
        } else {
            self::$translations = require __DIR__ . "/../Lang/" . self::$default . ".php";
        }

        // Define constante global
        define('APP_LANG', $lang);
    }

    public static function get($key) {
        return self::$translations[$key] ?? $key;
    }
}
