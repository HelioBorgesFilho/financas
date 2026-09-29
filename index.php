<?php

require_once __DIR__ . '/config/autoload.php';

use core\Env;

$envPath  = __DIR__ . '/.env';
Env::load($envPath);

header('Access-Control-Allow-Origin: *'); // em produçao usar = header('Access-Control-Allow-Origin: https://meusite.com');
header('Content-Type: application/json');
date_default_timezone_set("America/Sao_Paulo");

$headers = apache_request_headers(); // retirar e levar para parte de autenticação

$method  = $_SERVER['REQUEST_METHOD'];
$uri     = $_SERVER['REQUEST_URI'];
$routes  = explode('/', trim(parse_url($uri, PHP_URL_PATH), '/'));

$content = json_decode(file_get_contents('php://input'), true);

require_once __DIR__ . '/routes/routes.php';