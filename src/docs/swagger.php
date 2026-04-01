<?php

namespace Src\docs;

use OpenApi\Generator;

class Swagger
{
    public static function generateJson(): string
    {
        $openapi = (new Generator())->generate([
            __DIR__ . '/../controllers'
        ]);

        $spec = json_decode($openapi->toJson(), true);
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        $spec['servers'] = [
            ['url' => "{$scheme}://{$host}{$basePath}", 'description' => 'Servidor atual']
        ];

        return json_encode($spec, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}