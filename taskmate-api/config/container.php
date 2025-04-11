<?php

use App\Core\TokenBlacklistInterface;
use App\Services\TokenBlacklistRedisService;
use DI\Container;
use Dotenv\Dotenv;
use App\Core\JwtHandler;
use App\Core\TokenHandlerInterface;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\AuthenticatedUserMiddleware;
use Predis\Client as RedisClient;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return function (): Container {
    $container = new Container();

    $settings = require __DIR__ . '/settings.php';
    $container->set('settings', fn () => $settings);

    $container->set(PDO::class, function () use($settings) {
        $db = $settings['db'];
        return new PDO(
            "pgsql:host={$db['host']};port={$db['port']};dbname={$db['dbname']}",
            $db['user'],
            $db['pass']
        );
    });

    $container->set(RedisClient::class, function () use($settings) {
        $redisSettings = $settings['redis'];
        return new RedisClient([
            'scheme' => 'tcp',
            'host' => $redisSettings['host'],
            'port' => $redisSettings['port'],
        ]);
    });

    $container->set(TokenHandlerInterface::class, fn () => 
        new JwtHandler($settings['jwt_secret'] ?? 'secret')
    );

    $container->set(TokenBlacklistInterface::class, fn ($c) =>
        new TokenBlacklistRedisService($c->get(RedisClient::class))
    );

    $container->set(UserRepository::class, fn ($c) => 
        new UserRepository($c->get(PDO::class))
    );

    $container->set(AuthService::class, fn ($c) =>
        new AuthService(
            $c->get(UserRepository::class),
            $c->get(TokenHandlerInterface::class),
            $c->get(TokenBlacklistInterface::class)
        )
    );

    $container->set(AuthMiddleware::class, fn ($c) =>
        new AuthMiddleware(
            $c->get(TokenHandlerInterface::class),
            $c->get(UserRepository::class),
            $c->get(TokenBlacklistInterface::class)
        )
    );

    $container->set(AuthenticatedUserMiddleware::class, fn($c) =>
        new AuthenticatedUserMiddleware(
            $c->get(UserRepository::class)
        )
    );

    return $container;
};
