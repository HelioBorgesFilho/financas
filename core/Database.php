<?php

namespace core;
use core\Config;

class Database
{
    private static $instance = null;
    private function __construct() {}

    public static function getConnection()
    {
        $host     = Config::get('DB_HOST');
        $port     = Config::get('DB_PORT');
        $db_name  = Config::get('DB_NAME');
        $username = Config::get('DB_USER');
        $password = Config::get('DB_PASS');

        if (self::$instance === null) {
            try {
                self::$instance = new \PDO(
                    "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db_name . ";charset=utf8",
                    $username,
                    $password
                );
                self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\Exception $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}