<?php

use App\Controllers\UserController;
use App\Middlewares\AuthenticatedUserMiddleware;
use App\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Psr\Container\ContainerInterface;

return function (RouteCollectorProxy $group, ContainerInterface $container) {
    $group->get('/me', [UserController::class, 'me'])
        ->add($container->get(AuthenticatedUserMiddleware::class))
        ->add($container->get(AuthMiddleware::class));
};
