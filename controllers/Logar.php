<?php

namespace controllers;

use services\JwtService;
use models\Usuarios;

class Logar {

    public function logando($data){

        $Controller = new Controller;

        $email = trim($data['email']);
        $senha = trim($data['senha']);

        if ($data['email'] != null && $data['senha'] != null) {

            $Usuarios = new Usuarios;

            $usuario = $Usuarios->usuario($email);

            if ($email == $usuario['email'] && $senha == $usuario['senha']) {

                $JwtService = new JwtService;

                $gerador = $JwtService->generateToken($usuario);

                $expiracao = date('c', $gerador['expiracao']);

                $mensagem = [
                    "token_acesso" => $gerador['token'],
                    "token_acesso_expira_em" => $expiracao,
                    "usuario" => [
                      "id" => $usuario['id'],
                      "nome" => $usuario['nome'],
                      "email" => $usuario['email'],
                      "email_pendente" => $usuario['email_pendente'],
                      "foto_url" => $usuario['foto_url'],
                      "cor" => $usuario['cor'],
                      "idade" => $usuario['idade'],
                      "papel" => "membro",
                      "assinatura" => [ "plano" => "standard", "nome" => "Standard" ],
                    ]
                ];
                $codigo = 200;
            } else {

                $mensagem = $Controller->modeloRespostaErro('credenciais_invalidas', 'E-mail ou senha incorretos.');
                $codigo = 401;
            }
        } else {

            if ($data['email'] == null && $data['senha'] == null) {
                
                $mensagem = $Controller->modeloRespostaErro('validação', 'JSON mal formado!');
                $codigo = 400;
            }else{

                $campos = ['email' => $email, 'senha' => $senha];

                $mensagem = $Controller->modeloRespostaErro('validação', 'Preencha os campos obrigatórios destacados.', $campos);
                $codigo = 422;
            }
        }

        $resposta = $Controller->resposta($codigo, $mensagem);

        return $resposta;   
    }
}