<?php

namespace controllers;

use middleware\AuthMiddleware;
use controllers\Resposta;
use controllers\UsuariosController;
use services\AuthService;
use controllers\FormasPagamentoController;
use services\UsuarioFormaPagamentoService;

class Controller
{

    public function checkingRoutes($route, $numberOfRoute)
    {
        if ($numberOfRoute < 1 || $numberOfRoute > 10) {
            return false;
        }
        return true;
    }

    public function mainController($option, $data = [], $id = null)
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

                $resposta = $Resposta->resposta($sessaoAberta['codigo'], $sessaoAberta['mensagem']);

                break;

            case 'formasPagamento':

                $acesso = $AuthMiddleware->validarAcesso();

                if ($acesso['status']) {

                    $UsuariosController = new FormasPagamentoController;

                    $resposta = $UsuariosController->cadastrarPagamento(
                        $data
                    );

                } else {

                    return $Resposta->modeloRespostaErro(
                        $acesso['codigo'],
                        $acesso['mensagem'],
                    );
                }

                break;

            case 'usuarioFormaPagamento':

                $acesso = $AuthMiddleware->validarAcesso();

                if ($acesso['status']) {

                    $FormasPagamentoController = new FormasPagamentoController;

                    $resposta = $FormasPagamentoController->listarPagamentos();
                } else {

                    return $Resposta->modeloRespostaErro(
                        $acesso['codigo'],
                        $acesso['mensagem'],
                    );
                }

                break;

            case 'excluirFormaPagamento':

                $acesso = $AuthMiddleware->validarAcesso();

                if ($acesso['status']) {

                    $FormasPagamentoController = new FormasPagamentoController;

                    $resposta = $FormasPagamentoController->excluirPagamento($data);
                } else {

                    return $Resposta->modeloRespostaErro(
                        $acesso['codigo'],
                        $acesso['mensagem'],
                    );
                }

                break;

            case 'editarFormaPagamento':

                $acesso = $AuthMiddleware->validarAcesso();

                if ($acesso['status']) {

                    $FormasPagamentoController = new FormasPagamentoController;

                    $resposta = $FormasPagamentoController->editarPagamentos($id, $data);
                } else {

                    return $Resposta->modeloRespostaErro(
                        $acesso['codigo'],
                        $acesso['mensagem'],
                    );
                }

                break;

            default:

                $resposta = $Resposta->modeloRespostaErro(404, 'A requisição não foi encontrada!');
        }
        return $resposta;
    }
}