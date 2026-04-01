<?php

namespace Src\service;

use Src\service\IService\IUserService;
use Src\models\User;
use Src\data\IRepository\IUserRepository;
use Exception;


class UserService implements IUserService
{
    private IUserRepository $repository;

    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function GetUserById(string $id): User
    {
        try {
            $user = $this->repository->GetUserById($id);

            if ($user === null)
                throw new Exception("Usuario não existe.");
            return $user;
        } catch (\Throwable $th) {
            throw new Exception("Erro : " . $th->getMessage());
        }
    }

    public function GetAllUsers(): array
    {
        try {
            $users = $this->repository->GetAllUser();

            if (empty($users))
                throw new Exception("Nenhum usuário cadastrado.");
            return $users;
        } catch (\Throwable $th) {
            throw new Exception("Erro : " . $th->getMessage());
        }
    }

    public function GetUserByEmail(string $email): User
    {
        try {
            $user = $this->repository->GetUserByEmail($email);

            if ($user === null)
                throw new Exception("Usuario não existe.");
            return $user;
        } catch (\Throwable $th) {
            throw new Exception("Erro : " . $th->getMessage());
        }
    }

    public function AtualizarSenha(int $id, string $novaSenha): bool
    {
        try {
            $user = $this->repository->GetUserById($id);

            if ($user === null) {
                throw new Exception("Usuário não encontrado.");
            }

            $user->AtualizaSenha($novaSenha);
            $this->repository->UpdateUser($user);

            return true;
        } catch (\Throwable $th) {
            throw new Exception("Erro ao atualizar a senha: " . $th->getMessage());
        }
    }

    public function VerificarSenhaAtual(int $id, string $senhaAtual): bool
    {
        try {
            $user = $this->repository->GetUserById($id);

            if ($user === null) {
                throw new Exception("Usuário não encontrado.");
            }

            return $user->VerificaSenha($senhaAtual);
        } catch (\Throwable $th) {
            throw new Exception("Erro ao verificar a senha atual: " . $th->getMessage());
        }
    }

    public function VerificarEmail(string $email): User
    {
        try {
            $user = $this->repository->GetUserByEmail($email);

            if ($user === null) {
                throw new Exception("Email não cadastrado.");
            }

            return $user;
        } catch (\Throwable $th) {
            throw new Exception("Erro ao verificar o email: " . $th->getMessage());
        }
    }
}