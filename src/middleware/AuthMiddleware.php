<?php

namespace Src\middleware;

use Src\middleware\IMiddleware;
use Src\core\Http\HttpException;

class AuthMiddleware implements IMiddleware
{
    public function handle(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (empty($header) || !str_starts_with($header, 'Bearer ')) {
            throw new HttpException('Token nao fornecido', 401);
        }

        $token = substr($header, 7);

        // Aqui voce valida o token (JWT, banco, etc.)
        // Exemplo simples:
        if (empty($token)) {
            throw new HttpException('Token invalido', 401);
        }

        // Se valido, pode guardar dados do usuario para o controller acessar
        // $_REQUEST['user_id'] = $decoded->user_id;
    }
}
