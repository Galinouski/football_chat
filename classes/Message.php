<?php

namespace classes;

use PDO;

class Message
{
    private $message;
    private $users_id;

    private array $downloads_array;

    public function __construct($message, $users_id, array $downloads_array){
        $this->message=$message;
        $this->users_id=$users_id;
        $this->downloads_array=$downloads_array;
    }

    public function add_message(PDO $pdo): bool
    {

        $sql = "INSERT INTO `messages` (`users_id`, `message`, `txt_file_upload_name`, `image_file_upload_name` ) VALUES (:users_id, :message, :txt_file_upload_name, :image_file_upload_name)";

        // подготовленное SQL-выражение
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':users_id', $this->users_id);
        $stmt->bindParam(':message', $this->message);
        $stmt->bindParam(':txt_file_upload_name', $this->downloads_array['txt_file']);
        $stmt->bindParam(':image_file_upload_name', $this->downloads_array['image_file']);

        if (!$stmt->execute()) {
            return false;
        };

        return true;
    }

    public static function count_messages(PDO $pdo): int
    {
        $sql = "SELECT * FROM `messages`";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $sqlResultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$sqlResultArray) {
            return false;
        };

        return count($sqlResultArray);
    }
}