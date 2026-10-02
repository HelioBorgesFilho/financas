<?php

use controllers\Controller;

use middleware\AuthMiddleware;

$AuthMiddleware = new AuthMiddleware;
$controller = new Controller();

$routeExists = $controller->checkingRoutes($routes);

$mainRoute = '';

switch($method){

    case 'POST':

        $acesso = $AuthMiddleware->handle();

        break;
        
    case 'GET':

        break;

    case 'PUT':

        break;
    
}