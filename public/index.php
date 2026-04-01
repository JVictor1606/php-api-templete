<?php

use Src\core\Http\JsonResponse;
use Src\core\Http\Router;
use Src\core\Logger;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x-csrtoken, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require __DIR__ . '/../src/config/dependencies.php';


$router = new Router();

$routes = require __DIR__ . '/../src/routes/web.php';
$routes($router);

try {
    $router->run();
} catch (\Throwable $th) {
    Logger::error('Erro no index.php: ' . $th->getMessage());
    echo JsonResponse::error('Erro interno do servidor', 500);
}
