<?php

global $pdo;

if (empty($_REQUEST)) {
    // Подключение шаблона

    $context = [];

    if (isset($_COOKIE['registration'])) {
        render ('authorisation', $context);
    }
    else render ('registration', $context);

} else {
    session_start();
    // валидация введённых данных
    $errors = [];
    $context = [];

    if (!isset($_SESSION['pageShow'])){
        $_SESSION['pageShow'] = MAX_PAGES_PER_PAGE;
    }

    if(isset($_REQUEST['select_pages_show'])) {
        $_SESSION['pageShow'] = htmlspecialchars($_REQUEST['select_pages_show'], ENT_QUOTES);
    }


    if (isset($_POST['registration'])) {

        $userName = htmlspecialchars($_POST['userName'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);

        // функция сохранения в базу данных о пользователе
        if (!user_registration($pdo, $userName, $password, $email)) {

            $errors = "registration failed";
            $context = ['errors'=>$errors];
            render ('registration', $context);
            exit();
        }

        setcookie('registration', 1, time() + 3600, '/');
        setcookie('authorisation', 1, time() + 3600, '/');
    }

    if (isset($_POST['authorisation'])) {

        $userName = htmlspecialchars($_POST['userName'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

        // функция проверки пользователя
        if (!user_check($pdo, $userName, $password)) {

            $errors = "please check your username or password";
            $context = ['errors'=>$errors];
            render ('authorisation', $context);
            exit();
        }

        setcookie('authorisation', 1, time() + 3600, '/');
    }

    if (isset($_POST['main'])) {

        if (!isset($_COOKIE['authorisation'])) {
            render ('authorisation', $context);
            exit();
        }

        $message = htmlspecialchars($_POST['message'], ENT_QUOTES);

        if($_FILES['txtFile']['size'] > 100){
            $errors [] = "sorry your text file is too large!";
        };

        if ($_FILES['txtFile']['tmp_name']) {
            if (mime_content_type($_FILES['txtFile']['tmp_name']) == 'text/plain'){
                $txtFileName = $_FILES['txtFile']['tmp_name'];
            } else {
                $errors[] = "only text files are allowed";
            }
        }

        if ($_FILES['imageFile']['tmp_name']) {
            if (mime_content_type($_FILES['imageFile']['tmp_name']) == 'image/gif' || mime_content_type($_FILES['imageFile']['tmp_name']) == 'image/jpeg' || mime_content_type($_FILES['imageFile']['tmp_name']) == 'image/png') {
                $imageFileName = $_FILES['imageFile']['tmp_name'];
                $size = getimagesize($imageFileName);
                if ($size[0] > 320 || $size[1] > 240) {
                    $errors[] = "too big image resolution!";
                }
            } else {
                $errors[] = "only (.jpg, .gif, .png) files are allowed.";
            }
        }

        // функция добавления сообщений и файлов в бд

        $id = $_SESSION['id'];

        if (!add_users_message ($pdo, $message, $id)) {
            $errors [] = "sorry your message could not be sent.";
        }

    }

    if ($errors) {
        $context = ['errors'=>$errors];
        render('main', $context);
        exit();
    }

    if (!isset($_SESSION['sort'])) {
        $_SESSION['sort'] = 'desc';
    }

    $sort_array = [];

    if (isset($_POST['date_sort'])) {

        if ($_SESSION['sort'] == 'asc') {
            $_SESSION['sort'] = 'desc';
        }else {
            $_SESSION['sort'] = 'asc';
        }

        $sort_array = [
            'sort_field' => 'date',
            'sort_type' => $_SESSION['sort']
        ];
    }
    if (isset($_POST['name_sort'])) {
        if ($_SESSION['sort'] == 'asc') {
            $_SESSION['sort'] = 'desc';
        }else {
            $_SESSION['sort'] = 'asc';
        }

        $sort_array = [
            'sort_field' => 'name',
            'sort_type' => $_SESSION['sort']
        ];
    }
    if (isset($_POST['email_sort'])) {
        if ($_SESSION['sort'] == 'asc') {
            $_SESSION['sort'] = 'desc';
        }else {
            $_SESSION['sort'] = 'asc';
        }

        $sort_array = [
            'sort_field' => 'email',
            'sort_type' => $_SESSION['sort']
        ];
    }

    if(isset($_SESSION['page'])){
        $page = $_SESSION['page'];
    }
    if (isset($_GET['page'])) {
        $page = htmlspecialchars($_GET['page'], ENT_QUOTES);
        $_SESSION['page'] = $page;
    }
    else {
        $page = 1;
    }

    $select_pages_show = $_SESSION['pageShow'];
    $all_messages_count = count_messages($pdo);

    $chat_data = get_messages($pdo, $page, $select_pages_show, $sort_array);

    if (($all_messages_count / $select_pages_show) < 0) {
        $messages_pages_count = 1;
    }else {
        $messages_pages_count = ceil($all_messages_count / $select_pages_show);
    }

    // функция получения массива с сообщениями
    $context = [
        'chat_body'=> $chat_data,
        'pages'=> $messages_pages_count
    ];

    //вывести основной шаблон с массивом сообщений
    render('main', $context);
}
