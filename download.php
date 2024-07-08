<?php
if(isset($_GET['path']))
{
    //Читать url
    $url = $_GET['path'];
    //Очистить кэш
    clearstatcache();

    //Проверяем, существует ли путь к файлу или нет
    if(file_exists($url)) {

    //Определение информации заголовка
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($url).'"');
        header('Content-Length: '. filesize($url));
        header('Pragma: public');

    //Очистить выходной буфер системы
        flush();

    //Считаем размер файла
        readfile($url,true);

    //Завершить работу со скриптом
        die();
    }
    else{
        echo "Путь к файлу не существует. ";
    }
}
echo "Путь к файлу не определен.";