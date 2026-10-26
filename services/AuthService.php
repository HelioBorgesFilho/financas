<?php

namespace services;

use services\JwtService;
use models\Usuarios;
use config\Session;
use controllers\Resposta;

class AuthService {

    public function logando($data){

        $Resposta = new Resposta;

        $email = trim($data['email']);
        $senha = trim($data['senha']);

        if ($data['email'] != null && $data['senha'] != null) {

            $Usuarios = new Usuarios;

            $usuario = $Usuarios->usuario($email);

            if ($email == $usuario['email'] && $senha == $usuario['senha']) {

                $JwtService = new JwtService;

                $gerador = $JwtService->generateToken($usuario);

                session_regenerate_id(true);

                $duracao = 30 * 24 * 60 * 60; // 30 dias

                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['expira_em'] = time() + $duracao;

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

                $mensagem = $Resposta->modeloRespostaErro('credenciais_invalidas', 'E-mail ou senha incorretos.');
                $codigo = 401;
            }
        } else {

            if ($data['email'] == null && $data['senha'] == null) {
                
                $mensagem = $Resposta->modeloRespostaErro('validação', 'JSON mal formado!');
                $codigo = 400;
            }else{

                $campos = ['email' => $email, 'senha' => $senha];

                $mensagem = $Resposta->modeloRespostaErro('validação', 'Preencha os campos obrigatórios destacados.', $campos);
                $codigo = 422;
            }
        }

        $resposta = $Resposta->resposta($codigo, $mensagem);

        return $resposta;   
    }

    public static function sessaoAberta(){

        $session = Session::verificarSessao();        

        if($session['logado']){

            $Usuarios = new Usuarios;
            $JwtService = new JwtService;            

            $usuario = $Usuarios->detalhes($session["usuario_id"]);
            $token = $JwtService->generateToken($usuario);            
            return ['code' => 200, 'mensagem' => $token];
        }else{

            return ['code' => 401, 'mensagem' => 'Autenticação expirada.'];
        }
    }
}