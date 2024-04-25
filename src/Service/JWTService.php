<?php

namespace App\Service;

use DateTime;

class JWTService
{
//    public function __construct(
//        private readonly array $header,
//        private readonly array $payload,
//        private readonly string $secret,
//        private readonly string $signature,
//        private readonly string $base64Header,
//        private readonly string $base64Header,
//        private readonly string $base64Payload,
//        private readonly string $jwt,
//    )
//    {
//    }
    public function encoding(array $header,array $payload, string $secret, int $validity = 86400): string
    {
        if($validity > 0) {
            $now = new DateTime();
            $expiration = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $expiration;
        }
    //base64 ENCODING
    $base64Header = base64_encode(json_encode($header));
    $base64Payload = base64_encode(json_encode($payload));

    //CLEANING OF THE DATA (FORBIDDEN CHARACTERS REMOVAL [+, / =])
    $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
    $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);
    //SECURITY HANDLING

    $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
    $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    //FINAL STEP -> TOKEN GENERATION
        $jwt = $base64Header . '.' . $base64Payload . '.' . $signature;
        return $jwt;
    }

    public function check(string $token, $secret): bool
    {
    $header = $this->getHeader($token);
    $payload = $this->getPayload($token);
    $verificationToken = $this->encoding($header, $payload, $secret, 0);
    return $token === $verificationToken;
    }

    public function getHeader(string $token)
    {
        // Considering the token is based on 3 parts, we explode it to get them seperately
        $array = explode('.', $token);

        $header = json_decode(base64_decode($array[0]), true);
        return $header;
    }

    public function getPayload(string $token)
    {
        $array = explode('.', $token);

        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new DateTime();
        return $payload < $now->getTimestamp();
    }

    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-_=]+\.[a-zA-Z0-9\-_=]+\.[a-zA-Z0-9\-_=]+$/', $token
        ) === 1;
    }
}