<?php

namespace App\Core;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class JwtHandler implements TokenHandlerInterface
{
    private string $secret;
    private string $algo;

    public function __construct(string $secret, string $algo = 'HS256')
    {
        $this->secret = $secret;
        $this->algo = $algo;
    }

    public function generateToken(array $payload): string
    {
        return FirebaseJWT::encode($payload, $this->secret, $this->algo);
    }

    public function validateToken(string $token): object
    {
        return FirebaseJWT::decode($token, new Key($this->secret, $this->algo));
    }
}
