<?php

use Src\core\Http\Router;
use Src\controllers\HomeController;
use Src\controllers\DocsController;
use Src\controllers\UserController;

return function (Router $router) {

    // Documentacao
    $router->get('/api/v1/json', [DocsController::class, 'json']);

    // Home
    $router->get('/api/v1/', [HomeController::class, 'index']);

    // Users
    $router->get('/api/v1/users', [UserController::class, 'index']);
    $router->get('/api/v1/users/{id}', [UserController::class, 'show']);

};
