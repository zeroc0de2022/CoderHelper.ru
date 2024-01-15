<?php
namespace Coderhelper;
use Coderhelper\Components\Router;

// 1. Общие настройки
define('PUBLIC_HTML', dirname(__FILE__));
// 2. Подключение файлов системы
require 'vendor/autoload.php';
// 3. Вызов Router
$router = new Router();
$router->run();

 
