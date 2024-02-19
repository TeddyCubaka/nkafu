# Architecture

To say make things easy, this app look like this :

    + app
        + app/
        + documentation/
        + public/
        + vendor/
        + .env
        + composer.json
        + composer.lock

## `public` folder

The entry point of the app is in public. In this files you can put all you public files. Like, your html views and the index.php file for the server.

As you can see, the index.php file contain the all logic of the app.

## `documentation` folder

This folder contain the app documentation.

## `app` folder

There we are. This folder contain all your project logic. The router, database connection, etc..

His archicture look like this :

    + app/
        + confing/
        + controllers/
        + dao/
        + models/
        + modules/
        + database.php
        + router.php