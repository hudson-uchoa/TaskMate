<?php

namespace App\Services;

use App\Core\TokenBlacklistInterface;
use Predis\Client;

class TokenBlacklistRedisService implements TokenBlacklistInterface
{
    private Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function blacklist(string $token, int $ttl): void
    {
        $this->redis->setex("blacklist:$token", $ttl, 'blacklisted');
    }

    public function isBlacklisted(string $token): bool
    {
        return $this->redis->exists("blacklist:$token") > 0;
    }
}
