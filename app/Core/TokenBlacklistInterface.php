<?php

namespace App\Core;

interface TokenBlacklistInterface
{
    public function blacklist(string $token, int $ttl): void;
    public function isBlacklisted(string $token): bool;
}
