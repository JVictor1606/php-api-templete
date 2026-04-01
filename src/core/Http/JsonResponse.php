<?php

namespace Src\core\Http;

class JsonResponse
{
    public static function success(mixed $data = null, int $status = 200): string
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        return json_encode([
            'status' => $status,
            'data' => $data
        ]);
    }

    public static function error(string $message, int $status = 400, mixed $errors = null): string
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        $response = [
            'status' => $status,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return json_encode($response);
    }
}
