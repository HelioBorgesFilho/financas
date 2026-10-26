<?php

namespace services;

class JwtService
{
    private $secret = 'XAPOIDJJ8sd83auhuigs';

    public function generateToken( array $usuario) : array
    {

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $header = json_encode($header);
        $header = base64_encode($header);

        $expiracao = time() + (30 * 60);

        $payload = [
            'userid' => $usuario['id'],
            'email' => $usuario['email'],
            'exp' => $expiracao
        ];

        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secret, true);
        $signature = base64_encode($signature);

        $token = $header . '.' . $payload . '.' . $signature;
        return ['token' => $token, 'expiracao' => $expiracao];
    }

    public function validateJwt(string $token, $userId = null) : array
    {

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return ['status' => true, 'message' => 'Token invalido!'];
        }

        list($headerEncoded, $payloadEncoded, $signatureProvided) = $parts;

        $expectedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $expectedSignatureEncoded = base64_encode($expectedSignature);

        if ($expectedSignatureEncoded !== $signatureProvided) {
            return ['status' => false, 'message' => 'Token incorreto!'];
        }

        $payloadJson = base64_decode($payloadEncoded);
        $payload = json_decode($payloadJson, true);

        if (!$payload) {
            return ['status' => false, 'message' => 'Dados invalidos!'];
        }

        if($userId != null){
            if($payload['userid'] != $userId){
                return ['status' => false, 'message' => 'Dados invalidos!'];
            }
        }

        if (isset($payload['exp']) && time() > $payload['exp']) {
            return ['status' => false, 'message' => 'Expirado'];
        }

        return ['status' => true, 'message' => 'success'];
    }
}
