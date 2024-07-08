<?php

/**

 * @param PDO $pdo   - PDO-подключение к базе данных
 * @param string $username - имя пользователя чата
 * @param string $password - пароль пользователя чата
 * @param string $email - почта пользователя чата
 * @param int $id - идетификатор текущего пользователя
 * @param array $downloads_array - данные о приложениях к сообщению
 * @param int $page - текущая страница таблицы сообщений
 * @param int $select_pages_show - колличество сообщений на одной странице
 * @param array $sort_array - настройки сортировки (какое поле, тип сортировки)

 */

function user_registration( PDO $pdo, $username, $password, $email )
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
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
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

function user_check( PDO $pdo, $username, $password )
{
    $sql = "SELECT * FROM `users` WHERE username = '$username' AND password = '$password'";
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

function add_users_message (PDO $pdo, $message, $id, $downloads_array) {

    $sql = "INSERT INTO `messages` (`users_id`, `message`, `txt_file_upload_name`, `image_file_upload_name` ) VALUES (:users_id, :message, :txt_file_upload_name, :image_file_upload_name)";

    // подготовленное SQL-выражение
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':users_id', $id);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':txt_file_upload_name', $downloads_array['txt_file']);
    $stmt->bindParam(':image_file_upload_name', $downloads_array['image_file']);

    if (!$stmt->execute()) {
        return false;
    };

    return true;
}

function count_messages(PDO $pdo): int
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

function get_messages(PDO $pdo, $page, $select_pages_show,  $sort_array = []): array
{

    $start_page = $page * $select_pages_show - $select_pages_show;
    $end_page = $select_pages_show;

    if($sort_array) {

        $sort_type = $sort_array['sort_type'];

        switch ($sort_array['sort_field']){
            case 'date' :
                $sql = "SELECT messages.message, messages.date, messages.txt_file_upload_name, messages.image_file_upload_name, users.username, users.email FROM messages INNER JOIN users ON messages.users_id = users.id ORDER BY messages.date $sort_type LIMIT $start_page, $end_page ";
                break;
            case 'email' :
                $sql = "SELECT messages.message, messages.date, messages.txt_file_upload_name, messages.image_file_upload_name, users.username, users.email FROM messages INNER JOIN users ON messages.users_id = users.id ORDER BY users.email $sort_type LIMIT $start_page, $end_page ";
                break;
            case 'name' :
                $sql = "SELECT messages.message, messages.date, messages.txt_file_upload_name, messages.image_file_upload_name, users.username, users.email FROM messages INNER JOIN users ON messages.users_id = users.id ORDER BY users.username $sort_type LIMIT $start_page, $end_page ";
                break;
        }
    } else {
        $sql ="SELECT messages.message, messages.date, messages.txt_file_upload_name, messages.image_file_upload_name, users.username, users.email FROM messages INNER JOIN users ON messages.users_id = users.id ORDER BY messages.date DESC LIMIT $start_page, $end_page";
    }

    //var_dump($sql); die;
    // подготовленное SQL-выражение
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sqlResultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$sqlResultArray) {
        return [];
    };

    return $sqlResultArray;
}

// получение кокретной информации о браузере пользователя
function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

    return 'Other';
}

// функция отладки
function debug($data, $die = false)
{
    echo "<pre>" . print_r($data, 1) . "</pre>";
    if ($die) {
        die;
    }
}
