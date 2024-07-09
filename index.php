<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$base_path = __DIR__ . '\\';
require_once $base_path . 'configs\config.php';

//автозагрузка классов

require_once __DIR__ . '/vendor/autoload.php';

require_once $base_path . 'classes\db.php';


require_once $base_path . 'library\functions.php';
require_once $base_path . 'library\core.php';
require_once $base_path . 'router.php';
