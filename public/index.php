<?php

use Slim\Factory\AppFactory;

// Import the router, database, and autoload files
require '../app/router.php';
require '../app/database.php';
require '../vendor/autoload.php';

// Create a new Slim app instance
$app = AppFactory::create();

// Add error middleware if the environment is set to DEV
if ($_ENV['ENVIRONMENT'] === 'DEV') {
    $app->addErrorMiddleware(true, true, true);
}

try {
    // Create a new instance of the DatabaseConnector class
    $databaseConnector = new DatabaseConnector();

    // Get the database connection
    $db = $databaseConnector->get_connection();
} catch (PDOException $e) {
    // Handle any exceptions that occur during database connection
    echo $e->getMessage();
    exit(500);
}

// Create a new instance of the Router class and pass the Slim app and database connection
$router = new Router($app, $db);

// Call the routes() method to define the routes
$router->routes();

// Run the Slim app
$app->run();
