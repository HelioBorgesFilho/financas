<?php

require_once __DIR__ . '/config/autoload.php';

use core\Env;

$envPath  = __DIR__ . '/.env';
Env::load($envPath);

header('Access-Control-Allow-Origin: *'); // em produçao usar = header('Access-Control-Allow-Origin: https://meusite.com');
header('Content-Type: application/json');
date_default_timezone_set("America/Sao_Paulo");

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$path = parse_url($uri, PHP_URL_PATH) ?: '/';

$segments = explode('/', trim($path, '/'));

if (in_array($segments[0], ['financas', 'index.php'], true)) {
    array_shift($segments);
}

$routes = array_merge(['financas'], $segments);

$content = json_decode(file_get_contents('php://input'), true);

require_once __DIR__ . '/routes.php';