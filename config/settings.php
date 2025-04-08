<?php

return [
    'displayErrorDetails' => true,
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? '5432',
        'dbname' => $_ENV['DB_DATABASE'] ?? 'taskmate',
        'user' => $_ENV['DB_USERNAME'] ?? 'postgres',
        'pass' => $_ENV['DB_PASSWORD'] ?? 'root',
    ],
    'redis' => [
        'host' => $_ENV['REDIS_HOST'] ?? 'redis',
        'port' => $_ENV['REDIS_PORT'] ?? 6379,
    ],
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'secret'
];
