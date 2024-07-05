<?php
global $base_path;
global $pdo;

//require_once $base_path ."vendor\autoload.php";
require_once $base_path ."configs\database_config.php";
require_once $base_path ."library\database.php";
require_once $base_path . 'library\functions.php';
require_once $base_path . 'library\core.php';



render ('results', $context);
$dsn = NULL;




