<?php

namespace controllers;

use services\AuthService;
use Exception;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(array $data): void
    {
        try {

            if (empty($data['email']) || empty($data['password'])) {

                http_response_code(400);

                echo json_encode([
                    'success' => false,
                    'message' => 'E-mail e senha são obrigatórios'
                ]);

                return;
            }

            $result = $this->authService->login(
                $data['email'],
                $data['password']
            );

            http_response_code(200);

            echo json_encode([
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'data' => $result
            ]);

        } catch (Exception $e) {

            http_response_code(401);

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}