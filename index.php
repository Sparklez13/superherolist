<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once('./vendor/autoload.php');

use SuperHeroList\app\Application;

$config = require_once('config.php');

$app = new Application($config);
$app->run();