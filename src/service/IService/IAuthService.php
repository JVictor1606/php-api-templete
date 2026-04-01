<?php

namespace Src\service\IService;

use Src\models\User;

interface IAuthService
{
    public function Auth(string $email, string $senha): User;
}
