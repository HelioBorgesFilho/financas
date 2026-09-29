<?php

namespace core;

class Env {

    /**
     * Carrega as variáveis do arquivo .env para a memória do PHP.
     *
     * @param string $path O caminho completo até o arquivo .env
     * @return void
     */

    public static function load($path) {

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                
                list($name, $value) = explode('=', $line, 2);
                
                $name = trim($name);
                $value = trim($value);
                $value = trim($value, "\"'");

                putenv(sprintf('%s=%s', $name, $value));

                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    /**
     * * @return bool Retorna true se for dev, false se for produção.
     */
    public static function isDev()
    {
        $env = getenv('APP_ENV');
        $devEnvironments = ['localhost', 'sandbox', '127.0.0.1'];
        return in_array($env, $devEnvironments);
    }
}