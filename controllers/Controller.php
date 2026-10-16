<?php

namespace controllers;

use middleware\AuthMiddleware;
use services\JwtService;

class Controller
{

    private function errorResponse($code, $message)
    {
        return ['code' => $code, 'status' => ['message' => $message]];
    }

    public function response($code, $message) // teste, remover depois
    {
        return ['code' => $code, 'status' => ['message' => $message]];
    }

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

        switch ($option) {

            case 'familia':                

                $return = $AuthMiddleware->handle();

                if($return['status']){

                    $response = $this->response(200, $return['message']);

                }else{

                    $response = $this->response(401, $return['message']);
                }

                break;

            case 'login':

                if ($data['login'] != null && $data['password'] != null) {

                    $JwtService = new JwtService;

                    $return = $JwtService->generateToken($data);

                    $response = $this->response(200, $return);

                } elseif ($data['login'] == null && $data['password'] == null) {

                    $response = $this->response(401, 'Login e senha incorretas!');

                } elseif ($data['login'] == null) {

                    $response = $this->response(401, 'Login incorreto!');
                    
                } elseif ($data['password'] == null) {

                    $response = $this->response(401, 'Senha incorreta!');

                } else{

                    $response = $this->errorResponse(404, 'A requisição não foi encontrada!');
                }

                break;

            default:

                $response = $this->errorResponse(404, 'A requisição não foi encontrada!');
        }

        return $response;
    }
}