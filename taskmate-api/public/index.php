<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$buildContainer = require __DIR__ . '/../config/container.php';
$container = $buildContainer();

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();

(require __DIR__ . '/../config/routes.php')($app, $container);

$app->run();
