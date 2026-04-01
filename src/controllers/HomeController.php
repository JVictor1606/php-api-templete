<?php

namespace Src\controllers;

use OpenApi\Attributes as OA;
use Src\core\Http\JsonResponse;
use Src\core\Logger;

class HomeController
{
    #[OA\Get(
        path: '/api/v1/',
        summary: 'Endpoint de boas-vindas',
        tags: ['Home'],
        responses: [
            new OA\Response(response: 200, description: 'Sucesso'),
        ]
    )]
    public function index(): string
    {
        return JsonResponse::success(['message' => 'Bem-vindo a API!']);

    }

}
