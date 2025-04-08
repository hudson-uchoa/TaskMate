<?php

namespace App\Core;

interface TokenHandlerInterface
{
    public function generateToken(array $payload): string;

    public function validateToken(string $token): object;
}
