<?php

namespace config;

class Session {

    public static function ativar(){

        $duracao = 30 * 24 * 60 * 60; // 30 dias

        ini_set('session.use_strict_mode', '1');
        ini_set('session.gc_maxlifetime', (string) $duracao);

        session_set_cookie_params([
            'lifetime' => $duracao,
            'path' => '/',
            'secure' => false, // false no ambiente local com HTTP
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    } 

    public static function verificarSessao(): array
    {
        if (
            !isset($_SESSION['usuario_id'], $_SESSION['expira_em'])
            || time() >= $_SESSION['expira_em']
        ) {
            return ['logado' => false];
        }

        return [
            'logado' => true,
            'usuario_id' => $_SESSION['usuario_id'],
        ];
    }
}