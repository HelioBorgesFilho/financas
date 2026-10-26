<?php

namespace controllers;

use controllers\Resposta;
use models\Usuarios;
use services\AuthService;


class UsuariosController
{

    public function validarLog(array $data)
    {

        $Resposta = new Resposta;

        $email = trim($data['email']);
        $senha = trim($data['senha']);

        if ($data['email'] != null && $data['senha'] != null) {

            $Usuarios = new Usuarios;

            $usuario = $Usuarios->usuario($email);

            if ($email == $usuario['email'] && $senha == $usuario['senha']) {

                $AuthService = new AuthService;

                $logando = $AuthService->logando($usuario);
                $codigo = 200;
                $mensagem = $logando['mensagem'];
            } else {

                $mensagem = $Resposta->modeloRespostaErro('credenciais_invalidas', 'E-mail ou senha incorretos.');
                $codigo = 401;
            }

        } else {

            if ($data['email'] == null && $data['senha'] == null) {

                $mensagem = $Resposta->modeloRespostaErro('validação', 'JSON mal formado!');
                $codigo = 400;
            } else {

                $campos = ['email' => $email, 'senha' => $senha];

                $mensagem = $Resposta->modeloRespostaErro('validação', 'Preencha os campos obrigatórios destacados.', $campos);
                $codigo = 422;
            }
        }

        $resposta = $Resposta->resposta($codigo, $mensagem);

        return $resposta;
    }
}