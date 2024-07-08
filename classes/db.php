<?php

class DB
{
    // Переменная, хранящая объект PDO
    private static $pdo;

    private function __construct(){}

    public static function getInstance( ) {

        global $base_path;
        if(!self::$pdo){

            $ini_array = parse_ini_file($base_path . 'configs\database_config.ini');

            $host = $ini_array['server'];
            $db = $ini_array['database'];
            $charset = $ini_array['charset'];
            $username = $ini_array['username'];
            $password = $ini_array['password'];

            $opts= [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];


            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            self::$pdo = new PDO($dsn, $username, $password, $opts);

        }
        return self::$pdo;
    }
}