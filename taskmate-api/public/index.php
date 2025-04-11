<?php

use Slim\Factory\AppFactory;
use App\Middlewares\CorsMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$buildContainer = require __DIR__ . '/../config/container.php';
$container = $buildContainer();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->add(CorsMiddleware::class);

$app->options('/{routes:.+}', fn ($request, $response) =>  $response);


(require __DIR__ . '/../config/routes.php')($app, $container);

$app->run();
