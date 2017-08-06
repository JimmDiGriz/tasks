<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

ini_set('session.user_cookies', 1);
ini_set('session.session.cookie_httponly', 1);

use Core\WebApplication;

defined('APP_PATH') or define('APP_PATH', __DIR__ . '/../');

require_once(APP_PATH . 'Core/Psr4Autoloader.php');

$loader = new Psr4Autoloader();

$loader->register();

$loader->addNamespace('Core', APP_PATH . 'Core');
$loader->addNamespace('Site', APP_PATH . 'Backend');

$config = require_once(APP_PATH . 'Backend/Config/main.php');

$app = new WebApplication($config); 

//set_error_handler(function($code, $message, $file, $line, $context) use ($app) {
//    $app->errorHandler($code, $message, $file, $line, $context);
//});

$app->run();
