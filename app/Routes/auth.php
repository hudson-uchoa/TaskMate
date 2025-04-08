<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\AuthController;

return function (RouteCollectorProxy $group) {
    $group->post('/register', [AuthController::class, 'register']);
    $group->post('/login',    [AuthController::class, 'login']);
};
