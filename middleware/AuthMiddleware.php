<?php

namespace middleware;

use services\JwtService;

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
            return ['status' => false, 'message' => 'Token não informado ou formato inválido'];
        }

        $response = $this->jwtService->validateJwt($token);

        if(!$response){

            return ['status' => $response, 'message' => 'Token inválido'];
        }

        return ['status' => $response, 'message' => 'sucess'];
    }

    public function route($routes) {

        $chosenRoute = [];

        foreach($routes as $key => $route) {
            
            $chosenRoute[$key] = $route;
        } 
        return $chosenRoute;
    }

}