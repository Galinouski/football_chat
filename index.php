<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$base_path = __DIR__ . '\\';
require_once $base_path . 'configs\config.php';
require_once $base_path . 'configs\database_config.php';
require_once $base_path . 'library\database.php';
require_once $base_path . 'library\functions.php';
require_once $base_path . 'library\core.php';
require_once $base_path . 'router.php';
