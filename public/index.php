<?php
session_start();
require_once "../vendor/autoload.php";

use App\App;

$path = str_replace('\\', '/', dirname(__DIR__));
$app = new App($path);

echo $app->run();