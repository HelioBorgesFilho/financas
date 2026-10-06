<?php

namespace controllers;

class Controller
{

    private function errorResponse($code, $message)
    {
        return ['code' => $code, 'status' => ['message' => $message]];
    }

    public function testeResponse($code, $message) // teste, remover depois
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

    public function mainController($option, $data = [], $token = null)
    {

        switch ($option) {

            case '':

                break;

            case ' ':

                break;

            case '  ':

                break;

            case '    ':

                break;

            case '   ':

                break;

            default:

                $response = $this->errorResponse(404, 'A requisição não foi encontrada!');
        }

        return $response;
    }
}
