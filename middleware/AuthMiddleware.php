<?php

namespace middleware;

use services\JwtService;
use Exception;

class AuthMiddleware
{
    private JwtService $jwtService;

    public function __construct()
    {
        $this->jwtService = new JwtService();
    }

    private function extractToken(array $headers): ?string
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

    public function handle()
    {
        $headers = apache_request_headers();
        $token = $this->extractToken($headers);

        if ($token === null) {
            throw new Exception('Token não informado ou formato inválido');
        }

        return $this->jwtService->validateJwt($token);
    }
}
