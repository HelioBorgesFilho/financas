<?php

namespace services;

use services\JwtService;
use models\Usuarios;
use config\Session;

class AuthService
{

    public function logando(array $usuario) : array
    {

        $JwtService = new JwtService;

        $gerador = $JwtService->generateToken($usuario);

        session_regenerate_id(true);
        $duracao = 30 * 24 * 60 * 60;

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
                "assinatura" => ["plano" => "standard", "nome" => "Standard"],
            ]
        ];
        
        return ['mensagem' => $mensagem];
    }

    public static function sessaoAberta()
    {

        $session = Session::verificarSessao();

        if ($session['logado']) {

            $Usuarios = new Usuarios;
            $JwtService = new JwtService;

            $usuario = $Usuarios->detalhes($session["usuario_id"]);
            $token = $JwtService->generateToken($usuario);
            return ['code' => 200, 'mensagem' => $token];
        } else {

            return ['code' => 401, 'mensagem' => 'Autenticação expirada.'];
        }
    }
}