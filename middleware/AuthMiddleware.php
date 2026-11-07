<?php

namespace middleware;

use services\JwtService;
use config\Session;

class AuthMiddleware
{
    private JwtService $jwtService;

    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    private function extrairToken(array $headers): ?string
    {
        $authorization = $headers['Authorization'] ?? '';
        $parts = explode(' ', trim($authorization), 2);

        if (
            count($parts) !== 2 ||
            strcasecmp($parts[0], 'Bearer') !== 0
        ) {
            return null;
        }

        $token = trim($parts[1]);

        return $token !== '' ? $token : null;
    }

    public function validarAcesso(): array
    {
        $session = Session::verificarSessao();

        if (!$session['status']) {
            return [
                'status' => false,
                'codigo' => 401,
                'mensagem' => $session
            ];
        }

        $headers = function_exists('getallheaders') ? getallheaders() : [];

        $token = $this->extrairToken($headers);

        if ($token === null) {
            return [
                'status' => false,
                'codigo' => 401,
                'mensagem' => 'Token não informado.'
            ];
        }

        $response = $this->jwtService->validateJwt($token, $session['usuario_id']);

        if (!$response['status']) {

            if ($response['mensagem'] === 'Expirado') {
                return [
                    'status' => false,
                    'codigo' => 401,
                    'mensagem' => $response['mensagem']
                ];
            }

            return [
                'status' => false,
                'codigo' => 401,
                'mensagem' => $response['mensagem']
            ];
        }

        return [
            'status' => true,
            'codigo' => 200,
            'mensagem' => $response['mensagem']
        ];
    }
}