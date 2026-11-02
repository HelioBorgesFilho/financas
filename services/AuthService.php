<?php

namespace services;

use services\JwtService;
use models\Usuarios;
use config\Session;
use models\GrupoFamilia;
use models\FormaPagamento;

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

        if ($usuario['id_familia']) {

            $GrupoFamilia = new GrupoFamilia;
            $Usuarios = new Usuarios;

            $grupo = $GrupoFamilia->detalhes($usuario['id_familia']);

            $familia = [
                "familia" => [
                    "id" => $grupo['id'],
                    "nome" => $grupo['nome'],
                    "fuso_horario" => $grupo['fuso_horario'],
                ]
            ];

            $acesso = $Usuarios->detalhesFamilia($usuario['id_familia']);            

            $membros["resumo"] = [
                        "membros" => $acesso["administradores"] + $acesso["membros"],
                        "por_papel" => ["administrador" => $acesso["administradores"], "membro" => $acesso["membros"]],
            ];

            $familia = array_merge($familia, $membros);

            $mensagem = array_merge($mensagem, $familia);
        }

        $FormaPagamento = new FormaPagamento;

        $pagamentos = $FormaPagamento->metodosPagamentos($usuario['id']);

        if(!empty($pagamentos)){

            $formas['formas_pagamento'] = [];

            foreach($pagamentos as $posicao => $pagamento){

                $formas['formas_pagamento'][$posicao] = ['nome' => $pagamento['nome'], 'tipo' => $pagamento['tipo']];
            }

            $mensagem = array_merge($mensagem, $formas);
        }
        
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