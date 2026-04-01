<?php

namespace Src\controllers;

use Src\docs\Swagger;
use OpenApi\Attributes as OA;

#[OA\Info(title: 'Estrutura API', version: '1.0.0')]
class DocsController
{
    public function json(): string
    {
        header('Content-Type: application/json');
        return Swagger::generateJson();
    }
}
