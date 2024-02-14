<?php

use Slim\Factory\AppFactory;

include('../app/router.php');
require __DIR__ . ('/../app/database.php');
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

if ($_ENV['ENVIRONMENT'] === 'DEVELOPPEMENT') $app->addErrorMiddleware(true, true, true);

try {
    $conx = new DatabaseConnector();
    $db = $conx->get_connection();
} catch (PDOException $e) {
    echo $e->getMessage();
    exit(500);
}


$routes = new Router($app, $db);
$routes->routes();

$app->run();
