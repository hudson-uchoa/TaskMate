<?php

use Slim\App;
use Psr\Container\ContainerInterface;

return function (App $app, ContainerInterface $container) {
    $app->group('/api/v1', function ($group) use ($container) {
        (require __DIR__ . '/../app/Routes/auth.php')($group);
        (require __DIR__ . '/../app/Routes/tasks.php')($group, $container);
    });
};
