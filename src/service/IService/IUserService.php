<?php

namespace Src\service\IService;


use Src\models\User;

interface IUserService
{
    public function VerificarEmail(string $email): User;
    public function AtualizarSenha(int $id, string $novaSenha): bool;
    public function VerificarSenhaAtual(int $id, string $senhaAtual): bool;
    public function GetUserById(string $id): User;

    public function GetUserByEmail(string $email): User;
    public function GetAllUsers(): array;
}
