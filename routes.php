<?php

use controllers\Controller;

use middleware\AuthMiddleware;

$AuthMiddleware = new AuthMiddleware;
$controller = new Controller();

$route = $AuthMiddleware->route($routes);

$numberOfRoute = count($route);

$routeExists = $controller->checkingRoutes($route, $numberOfRoute);

if (!$routeExists) {
    $response = $controller->mainController('error');
    http_response_code($response['code']);
    echo json_encode($response['status']);
    exit;
}

switch ($method) {

    case 'POST':

        switch ($route[1]) {

            case 'lancamentos':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/lancamentos'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }
                break;

            case 'formas-pagamento':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/formas-pagamento'); // teste, remover depois
                } else {
                    $response = $controller->mainController('error');
                }
                break;

            case 'limites-diarios':

                if ($numberOfRoute === 3 && $route[2] === 'distribuir') {

                    $response = $controller->testeResponse(200, '/limites-diarios/distribuir'); // teste, remover depois
                } else {
                    $response = $controller->mainController('error');
                }
                break;

            case 'eu':

                if ($numberOfRoute === 3 && $route[2] === 'foto') {

                    $response = $controller->testeResponse(200, '/eu/foto'); // teste, remover depois
                } elseif ($numberOfRoute === 3 && $route[2] === 'email') {

                    $response = $controller->testeResponse(200, '/eu/email'); // teste, remover depois
                } elseif ($route[2] === 'assinatura') {

                    if ($numberOfRoute === 3) {

                        $response = $controller->testeResponse(200, '/eu/assinatura'); // teste, remover depois
                    } elseif (
                        $numberOfRoute === 4 && $route[3] === 'restaurar') {

                        $response = $controller->testeResponse(200, '/eu/assinatura/restaurar'); // teste, remover depois
                    } else {
                        $response = $controller->mainController('error');
                    }
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'familia':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/familia'); // teste, remover depois
                } elseif ($route[2] === 'convites') {

                    if ($numberOfRoute === 3 && $route[2] === 'convites') {

                        $response = $controller->testeResponse(200, '/familia/convites'); // teste, remover depois
                    } elseif ($numberOfRoute === 5 && $route[2] === 'convites' && ctype_digit($route[3]) && $route[4] === 'reenviar') {

                        $response = $controller->testeResponse(200, '/familia/convites/{id}/reenviar'); // teste, remover depois
                    } else {
                        $response = $controller->mainController('error');
                    }
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'convites':

                if ($numberOfRoute === 4 && $route[1] === 'convites' && ctype_digit($route[2]) && $route[3] === 'aceitar') {

                    $response = $controller->testeResponse(200, '/convites/{codigo}/aceitar'); // teste, remover depois
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'auth':

                if ($numberOfRoute === 3 && $route[2] === 'login') {

                    $response = $controller->testeResponse(200, '/auth/login'); // teste, remover depois
                } elseif ($numberOfRoute === 3 && $route[2] === 'google') {

                    $response = $controller->testeResponse(200, '/auth/google'); // teste, remover depois
                } elseif ($numberOfRoute === 3 && $route[2] === 'esqueci-senha') {

                    $response = $controller->testeResponse(200, '/auth/esqueci-senha'); // teste, remover depois
                } elseif ($numberOfRoute === 3 && $route[2] === 'cadastro') {

                    $response = $controller->testeResponse(200, '/auth/cadastro'); // teste, remover depois
                } elseif ($numberOfRoute === 3 && $route[2] === 'sair') {

                    $response = $controller->testeResponse(200, '/auth/sair'); // teste, remover depois
                } else {
                    $response = $controller->mainController('error');
                }
                break;

            default:

                $response = $controller->mainController('error');
        }

        break;

    case 'GET':

        switch ($route[1]) {

            case 'inicio':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/inicio'); // teste, remover depois
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'categorias':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/categorias'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'formas-pagamento':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/formas-pagamento'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'familia':

                if ($route[1] === 'familia') {

                    if ($numberOfRoute === 2 && $route[1] === 'familia') {

                        $response = $controller->testeResponse(200, '/familia'); // teste, remover depois

                    } elseif ($numberOfRoute === 4 && $route[1] === 'familia' && $route[2] === 'limite-mensal' && $route[3] === 'simulacao') {

                        $response = $controller->testeResponse(200, '/familia/limite-mensal/simulacao'); // teste, remover depois

                    } else {
                        $response = $controller->mainController('error');
                    }
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'saude?mes=2026-09':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/saude?mes=2026-09'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'limites-diarios':

                if ($route[1] === 'limites-diarios') {

                    if ($numberOfRoute === 2 && $route[1] === 'limites-diarios') {

                        $response = $controller->testeResponse(200, '/limites-diarios'); // teste, remover depois

                    } elseif ($numberOfRoute === 4 && ctype_digit($route[2]) && $route[3] === 'simulacao') {

                        $response = $controller->testeResponse(200, '/limites-diarios/{usuario_id}/simulacao'); // teste, remover depois

                    } elseif ($numberOfRoute === 4 && $route[2] === 'distribuir' && $route[3] === 'simulacao') {

                        $response = $controller->testeResponse(200, '/limites-diarios/distribuir/simulGETacao'); // teste, remover depois

                    } else {
                        $response = $controller->mainController('error');
                    }
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'eu':

                if ($route[1] === 'eu') {

                    if ($numberOfRoute === 2 && $route[1] === 'eu') {

                        $response = $controller->testeResponse(200, '/eu'); // teste, remover depois

                    } elseif ($numberOfRoute === 3 && $route[2] === 'assinatura') {

                        $response = $controller->testeResponse(200, '/eu/assinatura'); // teste, remover depois

                    } else {
                        $response = $controller->mainController('error');
                    }
                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'convites':

                if ($numberOfRoute === 3 && ctype_digit($route[2])) {

                    $response = $controller->testeResponse(200, '/convites/{codigo}'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            default:

                $response = $controller->mainController('error');
        }
        break;

    case 'PATCH':

        switch ($route[1]) {

            case 'formas-pagamento':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/formas-pagamento'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'eu':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/eu'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'familia':

                if ($numberOfRoute === 4 && $route[2] === 'membros' && ctype_digit($route[3])) {

                    $response = $controller->testeResponse(200, '/familia/membros/{usuario_id}'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            default:

                $response = $controller->mainController('error');
        }

        break;

    case 'DELETE':

        switch ($route[1]) {

            case 'formas-pagamento':

                if ($numberOfRoute === 2) {

                    $response = $controller->testeResponse(200, '/formas-pagamento'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'limites-diarios':

                if ($numberOfRoute === 3 && ctype_digit($route[2])) {

                    $response = $controller->testeResponse(200, '/limites-diarios/{usuario_id}'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'eu':

                if ($numberOfRoute === 3 && $route[2] === 'foto') {

                    $response = $controller->testeResponse(200, '/eu/foto'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'familia':

                if ($numberOfRoute === 4 && $route[2] === 'convites' && ctype_digit($route[3])) {

                    $response = $controller->testeResponse(200, '/familia/convites/{id}'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            default:

                $response = $controller->mainController('error');
        }

        break;

    case 'PUT':

        switch ($route[1]) {

            case 'familia':

                if ($numberOfRoute === 3 && $route[2] === 'limite-mensal') {

                    $response = $controller->testeResponse(200, '/familia/limite-mensal'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'limites-diarios':

                if ($numberOfRoute === 3 && ctype_digit($route[2])) {

                    $response = $controller->testeResponse(200, '/limites-diarios/{usuario_id}'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            case 'eu':

                if ($numberOfRoute === 3 && $route[2] === 'senha') {

                    $response = $controller->testeResponse(200, '/eu/senha'); // teste, remover depois

                } else {
                    $response = $controller->mainController('error');
                }

                break;

            default:

                $response = $controller->mainController('error');
        }

        break;

    default:

        $response = $controller->mainController('error');
}

$code = $response['code'];
$info = $response['status'] ?? $response['data'];

http_response_code($code);
echo json_encode($info);
exit;
