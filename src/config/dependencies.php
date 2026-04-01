<?php

use Src\core\Container;

// Database
use Src\data\RepositoryBase;

// Repositories
use Src\data\IRepository\{
    IUserRepository,
};
use Src\data\Repository\{
    UserRepository,
};

// Services
use Src\service\IService\{
    IAuthService,
    IUserService
};
use Src\service\
{
    AuthService,
    UserService
};

// Controllers
use Src\controllers\UserController;

// Exemplo de configuração de dependências usando um container de injeção de dependências. Este arquivo define como as classes e interfaces estão relacionadas e como elas devem ser instanciadas quando necessárias.

// =====================
// DATABASE CONNECTION
// =====================


Container::set(RepositoryBase::class, function () {
    return new RepositoryBase();
});


// // =====================
// // REPOSITORIES
// // =====================
Container::set(IUserRepository::class, function () {
    return new UserRepository(
        Container::get(RepositoryBase::class)  // Injeta a conexão
    );
});






// // =====================
// // SERVICES
// // =====================
Container::set(IAuthService::class, function () {
    return new AuthService(
        Container::get(IUserRepository::class)  // Injeta o repositório
    );
});

Container::set(IUserService::class, function () {
    return new UserService(
        Container::get(IUserRepository::class)  // Injeta o repositório
    );
});





// // =====================
// // CONTROLLERS
// // =====================
Container::set(UserController::class, function () {
    return new UserController(
        Container::get(IUserService::class)
    );
});


