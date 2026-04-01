<?php

namespace Src\controllers;

use OpenApi\Attributes as OA;
use Src\core\Http\JsonResponse;
use Src\core\Http\HttpException;
use Src\service\IService\IUserService;

class UserController
{
    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    #[OA\Get(
        path: '/api/v1/users',
        summary: 'Listar todos os usuarios',
        tags: ['Users'],
        responses: [
            new OA\Response(response: 200, description: 'Lista de usuarios'),
        ]
    )]
    public function index(): string
    {
        $users = $this->userService->GetAllUsers();
        $data = array_map(fn($user) => $user->toArray(), $users);
        return JsonResponse::success($data);
    }

    #[OA\Get(
        path: '/api/v1/users/{id}',
        summary: 'Buscar usuario por ID',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Usuario encontrado'),
            new OA\Response(response: 404, description: 'Usuario nao encontrado'),
        ]
    )]
    public function show(int $id): string
    {
        try {
            $user = $this->userService->GetUserById($id);
            return JsonResponse::success($user->toArray());
        } catch (\Exception $e) {
            throw new HttpException('Usuario nao encontrado', 404);
        }
    }
}
