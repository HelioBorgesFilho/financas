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

    public function handle(?string $token): object
    {
        if (!$token) {
            throw new Exception('Token não informado');
        }

        return $this->jwtService->validateJwt($token, $userId);
    }
}