<?php

namespace services;

use PDO;
use Exception;
use services\JwtService;

class AuthService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function login(string $email, string $password): array
    {
        $sql = "
            SELECT
                id,
                name,
                email,
                password
            FROM users
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception('E-mail ou senha inválidos');
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception('E-mail ou senha inválidos');
        }

        $token = $this->generateToken($user);

        unset($user['password']);

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}