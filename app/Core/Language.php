<?php
namespace App\Core;

class Language {
    private static $supported = ['pt', 'en', 'es'];
    private static $default = 'pt';
    private static $translations = [];

    public static function init() {
        session_start();

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
