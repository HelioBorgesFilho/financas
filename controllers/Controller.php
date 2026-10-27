<?php

namespace controllers;

use middleware\AuthMiddleware;
use controllers\Resposta;
use controllers\UsuariosController;
use services\AuthService;

class Controller
{

    public function checkingRoutes($route, $numberOfRoute)
    {
        if ($numberOfRoute < 1 || $numberOfRoute > 10) {
            return false;
        }
        return true;
    }

    public function mainController($option, $data = [])
    {
        $AuthMiddleware = new AuthMiddleware;
        $Resposta = new Resposta;

        switch ($option) {

            case 'familia':

                $return = $AuthMiddleware->handle();

                if ($return['status']) {

                    $resposta = $Resposta->resposta(200, $return['mensagem']);
                } else {

                    $resposta = $Resposta->resposta(401, $return['mensagem']);
                }

                break;

            case 'login':

                $UsuariosController = new UsuariosController;
                $resposta = $UsuariosController->validarLog($data); 

                break;

            case 'cadastro':

                $UsuariosController = new UsuariosController;
                $resposta = $UsuariosController->cadastrar($data);

                break;

            case 'renovar':

                $sessaoAberta = AuthService::sessaoAberta();

                $resposta = $Resposta->resposta($sessaoAberta['code'], $sessaoAberta['mensagem']);

                break;

            default:

                $resposta = $Resposta->resposta(404, 'A requisição não foi encontrada!');
        }

        return $resposta;
    }
}