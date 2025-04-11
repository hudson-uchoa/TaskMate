<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\TaskController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\AuthenticatedUserMiddleware;
use Psr\Container\ContainerInterface;

return function (RouteCollectorProxy $group, ContainerInterface $container) {
    $group->group('/tasks', function ($taskGroup) {
        $taskGroup->post('',        [TaskController::class, 'create']);
        $taskGroup->put('/{id}',    [TaskController::class, 'update']);
        $taskGroup->delete('/{id}', [TaskController::class, 'delete']);
        $taskGroup->get('/{id}', [TaskController::class, 'getById']);
        $taskGroup->get('', [TaskController::class, 'getAll']);
    })
        ->add($container->get(AuthenticatedUserMiddleware::class))
        ->add($container->get(AuthMiddleware::class));
};
