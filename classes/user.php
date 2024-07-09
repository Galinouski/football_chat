<?php

namespace classes;

use PDO;

class User
{
    private $username;
    private $password;
    private $email;
    function __construct($username, $password, $email){
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }
    public function registration(PDO $pdo)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $browser = get_browser_name($_SERVER['HTTP_USER_AGENT']);

        //debug($browser, 1);

        $query_string = "CREATE TABLE IF NOT EXISTS users (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `username` TEXT NULL , `password` TEXT NULL , `email` TEXT NULL, `ip` TEXT NULL, `browser` TEXT NULL, PRIMARY KEY (`id`) ) ENGINE = InnoDB ";

        $stmt = $pdo->prepare($query_string);
        if (!$stmt->execute()) {
            return false;
        };

        $sql = "INSERT INTO users (username, password, email, ip, browser) VALUES (:username, :password, :email, :ip, :browser)";
        // подготовленное SQL-выражение
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':browser', $browser);

        if (!$stmt->execute()) {
            return false;
        };

        $_SESSION['id'] = $pdo->lastInsertId();

        $query_string = "CREATE TABLE IF NOT EXISTS messages (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `users_id` INT, `message` TEXT NULL, `txt_file_upload_name` TEXT NULL, `image_file_upload_name` TEXT NULL, `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`), KEY `users_id` (`users_id`)) ENGINE = InnoDB ";

        $stmt = $pdo->prepare($query_string);
        if (!$stmt->execute()) {
            return false;
        };

        return true;

    }

    public static function check( PDO $pdo, $userName, $password ): bool
    {
        $sql = "SELECT * FROM `users` WHERE username = '$userName' AND password = '$password'";
        // подготовленное SQL-выражение
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $sqlResultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$sqlResultArray) {
            return false;
        };

        $_SESSION['id'] = $sqlResultArray[0]['id'];

        return true;
    }

}