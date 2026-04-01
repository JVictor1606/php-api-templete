<?php

namespace Src\service;

use Src\service\IService\IAuthService;
use Src\models\User;
use Src\data\IRepository\IUserRepository;
use Exception;

class AuthService implements IAuthService
{
    private IUserRepository $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function Auth(string $email, string $senha): User
    {
        try {
            $user = $this->repository->GetUserByEmail($email);

            if ($user === null)
                throw new Exception("Usuario não existe");

            if (!$user->VerificaSenha($senha))
                throw new Exception("Usuario ou senha incorreto");
            return $user;
        } catch (\Throwable $th) {
            throw new Exception("Erro  : " . $th->getMessage());
        }
    }
}
