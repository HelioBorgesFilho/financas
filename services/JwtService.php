<?php

namespace services;

class JwtService
{
    private $secret = 'XAPOIDJJ8sd83auhuigs';

    public function generateToken($user)
    {

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $header = json_encode($header);
        $header = base64_encode($header);

        $expiration = time() + (3 * 60 * 60);

        $payload = [
            'userid' => $user['id'],
            'username' => $user['name'],
            'exp' => $expiration
        ];

        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', $header . '.' . $payload, $this->secret, true);
        $signature = base64_encode($signature);

        $token = $header . '.' . $payload . '.' . $signature;
        return $token;
    }

    public function validateJwt($token, $userId = null)
    {

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($headerEncoded, $payloadEncoded, $signatureProvided) = $parts;

        $expectedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $expectedSignatureEncoded = base64_encode($expectedSignature);

        if ($expectedSignatureEncoded !== $signatureProvided) {
            return false;
        }

        $payloadJson = base64_decode($payloadEncoded);
        $payload = json_decode($payloadJson, true);

        if (!$payload) {
            return false;
        }

        if($userId != null){
            if($payload['userid'] != $userId){
                return false;
            }
        }

        if (isset($payload['exp']) && time() > $payload['exp']) {
            return false;
        }

        return true;
    }
}
