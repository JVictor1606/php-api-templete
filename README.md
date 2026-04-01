# PHP API Template

Template para construir APIs REST em PHP puro com arquitetura MVC, sem framework.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)

---

## Recursos

- Router com suporte a GET, POST, PUT, DELETE e parametros dinamicos (`/users/{id}`)
- Container de Injecao de Dependencia (Singleton)
- Sistema de Middlewares (ex: autenticacao)
- Respostas JSON padronizadas (`JsonResponse`)
- Tratamento de erros com `HttpException`
- Swagger UI integrado para documentacao automatica
- Sistema de Logs (`Logger`)
- Variaveis de ambiente com `.env`
- Arquitetura em camadas: Controller > Service > Repository

---

## Arquitetura

```
Requisicao HTTP
       |
   index.php          CORS, .env, autoload, Container DI
       |
     Router            Encontra rota, executa middlewares
       |
   Middleware?         Valida token, permissoes (throw HttpException se falhar)
       |
   Controller          Recebe request, chama Service, retorna JsonResponse
       |
    Service            Logica de negocio, validacoes
       |
   Repository          Acesso ao banco (PDO)
       |
     Model             Entidade de dominio
```

---

## Estrutura de Pastas

```
├── public/
│   ├── index.php               # Entry point (Front Controller)
│   ├── .htaccess               # Rewrite para index.php
│   └── swagger/                # Swagger UI (arquivos estaticos)
│
├── src/
│   ├── config/
│   │   └── dependencies.php    # Registro de dependencias no Container
│   │
│   ├── core/
│   │   ├── Container.php       # Container de Injecao de Dependencia
│   │   ├── Logger.php          # Sistema de logs (info, warning, error)
│   │   └── Http/
│   │       ├── Router.php      # Sistema de rotas + middlewares
│   │       ├── JsonResponse.php # Respostas JSON padronizadas
│   │       └── HttpException.php # Exceptions com status HTTP
│   │
│   ├── middleware/
│   │   ├── IMiddleware.php     # Interface de middleware
│   │   └── AuthMiddleware.php  # Exemplo: validacao de token
│   │
│   ├── controllers/
│   │   ├── HomeController.php  # Exemplo de controller
│   │   ├── UserController.php  # Exemplo com Service injetado
│   │   └── DocsController.php  # Gera JSON da spec OpenAPI
│   │
│   ├── service/
│   │   ├── IService/           # Interfaces dos services
│   │   ├── AuthService.php     # Exemplo: autenticacao
│   │   └── UserService.php     # Exemplo: logica de usuario
│   │
│   ├── data/
│   │   ├── RepositoryBase.php  # Conexao PDO com o banco
│   │   ├── IRepository/        # Interfaces dos repositories
│   │   └── Repository/
│   │       └── UserRepository.php # Exemplo: queries de usuario
│   │
│   ├── models/
│   │   └── User.php            # Entidade de exemplo
│   │
│   ├── database/
│   │   └── banco.sql           # Script SQL para teste
│   │
│   ├── docs/
│   │   └── swagger.php         # Gerador da spec OpenAPI
│   │
│   └── routes/
│       └── web.php             # Definicao de rotas
│
├── logs/                       # Logs da aplicacao (criado automaticamente)
├── .env.example                # Modelo de variaveis de ambiente
├── .htaccess                   # Redireciona tudo para public/
└── composer.json               # Autoload PSR-4 e dependencias
```

---

## Instalacao

### Pre-requisitos

- PHP 8.0+
- MySQL 8.0+
- Composer ([como instalar](https://getcomposer.org/download/))
- Apache com `mod_rewrite` ativado

### Passo a passo

```bash
# 1. Clone o repositorio
git clone https://github.com/seu-usuario/php-api-template.git

# 2. Entre na pasta
cd php-api-template

# 3. Instale as dependencias (obrigatorio — a pasta vendor/ nao esta no repositorio)
composer install

# 4. Configure as variaveis de ambiente
cp .env.example .env
# Edite o .env com suas credenciais do banco

# 5. Importe o banco de dados de teste
mysql -u root -p < src/database/banco.sql

# 6. Acesse a API
# http://localhost/php-api-template/public/api/v1/

# 7. Acesse o Swagger UI
# http://localhost/php-api-template/public/swagger/index.html
```

> **Importante:** O comando `composer install` e obrigatorio. Ele gera a pasta `vendor/` com o autoload PSR-4 e as dependencias do projeto (swagger-php, phpdotenv). Sem ele, a aplicacao nao funciona.

---

## Banco de Dados de Teste

O arquivo `src/database/banco.sql` cria o banco `api_template_db` com a tabela `usuario_tb` e 5 usuarios fake para testes:

| Nome | Email | Senha |
|------|-------|-------|
| Joao Silva | joao@email.com | 123456 |
| Maria Santos | maria@email.com | 123456 |
| Pedro Oliveira | pedro@email.com | 123456 |
| Ana Costa | ana@email.com | 123456 |
| Carlos Souza | carlos@email.com | 123456 |

As senhas estao em bcrypt no banco. Para importar:

```bash
mysql -u root -p < src/database/banco.sql
```

Ou importe manualmente pelo phpMyAdmin / HeidiSQL / DBeaver.

---

## Configuracao do .env

Copie o `.env.example` para `.env` e edite com suas credenciais:

```env
DB_HOST=localhost
DB_NAME=api_template_db
DB_USER=root
DB_PASS=root

AMBIENTE=DEV
```

> Valores com espacos ou caracteres especiais devem usar aspas duplas: `CHAVE="valor com espacos"`

---

## Rotas

Definidas em `src/routes/web.php`:

```php
return function (Router $router) {

    // Documentacao
    $router->get('/api/v1/json', [DocsController::class, 'json']);

    // Home
    $router->get('/api/v1/', [HomeController::class, 'index']);

    // Users
    $router->get('/api/v1/users', [UserController::class, 'index']);
    $router->get('/api/v1/users/{id}', [UserController::class, 'show']);
};
```

### Verbos disponiveis

```php
$router->get('/rota', [Controller::class, 'metodo']);
$router->post('/rota', [Controller::class, 'metodo']);
$router->put('/rota/{id}', [Controller::class, 'metodo']);
$router->delete('/rota/{id}', [Controller::class, 'metodo']);
```

### Rotas com middleware

```php
// Um middleware
$router->get('/api/v1/users', [UserController::class, 'index'], [AuthMiddleware::class]);

// Multiplos middlewares (executam na ordem)
$router->delete('/api/v1/users/{id}', [UserController::class, 'destroy'], [
    AuthMiddleware::class,
    AdminMiddleware::class
]);
```

---

## JsonResponse

Todas as respostas da API usam `JsonResponse` para manter um padrao:

```php
// Sucesso (200)
return JsonResponse::success(['message' => 'Dados carregados']);
// {"status": 200, "data": {"message": "Dados carregados"}}

// Sucesso com status customizado (201)
return JsonResponse::success($user->toArray(), 201);
// {"status": 201, "data": {"id": 1, "nome": "Joao"}}

// Erro
return JsonResponse::error('Nao encontrado', 404);
// {"status": 404, "message": "Nao encontrado"}

// Erro com detalhes de validacao
return JsonResponse::error('Dados invalidos', 422, ['email' => 'Campo obrigatorio']);
// {"status": 422, "message": "Dados invalidos", "errors": {"email": "Campo obrigatorio"}}
```

---

## Middlewares

Middlewares rodam antes do controller. Se precisam barrar a requisicao, lancam `HttpException`:

```php
class AuthMiddleware implements IMiddleware
{
    public function handle(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (empty($header) || !str_starts_with($header, 'Bearer ')) {
            throw new HttpException('Token nao fornecido', 401);
        }

        // Validar token aqui (JWT, banco, etc.)
    }
}
```

---

## Logger

Sistema de logs simples que grava em `logs/app.log`. A pasta e criada automaticamente.

```php
use Src\core\Logger;

Logger::info('Usuario 5 autenticado');
Logger::warning('Token expirado para user_id 3');
Logger::error('Falha ao conectar ao banco');
```

Resultado no arquivo:

```
[2026-03-31 14:30:22] INFO: Usuario 5 autenticado
[2026-03-31 14:30:25] WARNING: Token expirado para user_id 3
[2026-03-31 14:31:10] ERROR: Falha ao conectar ao banco
```

---

## Container de Dependencias

Registre suas dependencias em `src/config/dependencies.php`:

```php
// Conexao
Container::set(RepositoryBase::class, fn() => new RepositoryBase());

// Repository: interface -> implementacao
Container::set(IUserRepository::class, fn() =>
    new UserRepository(Container::get(RepositoryBase::class))
);

// Service
Container::set(IUserService::class, fn() =>
    new UserService(Container::get(IUserRepository::class))
);

// Controller
Container::set(UserController::class, fn() =>
    new UserController(Container::get(IUserService::class))
);
```

---

## Swagger UI

A documentacao da API e gerada automaticamente a partir de annotations nos controllers.

### Acessar

```
http://localhost/seu-projeto/public/swagger/index.html
```

### Adicionar annotations nos controllers

```php
use OpenApi\Attributes as OA;

class UserController
{
    #[OA\Get(
        path: '/api/v1/users',
        summary: 'Listar usuarios',
        tags: ['Users'],
        responses: [
            new OA\Response(response: 200, description: 'Sucesso'),
            new OA\Response(response: 401, description: 'Nao autorizado'),
        ]
    )]
    public function index(): string
    {
        return JsonResponse::success($users);
    }
}
```

O `#[OA\Info]` deve existir em pelo menos um controller:

```php
#[OA\Info(title: 'Nome da API', version: '1.0.0')]
class DocsController { ... }
```

---

## Padroes de Projeto

| Padrao | Onde |
|--------|------|
| **MVC** | Controllers, Models, Services |
| **Repository Pattern** | `data/Repository/` — abstrai acesso ao banco |
| **Dependency Injection** | `Container.php` — resolve e injeta dependencias |
| **Front Controller** | `public/index.php` — unico entry point |
| **Interface Segregation** | `IService/`, `IRepository/` — contratos |
| **Middleware** | `middleware/` — intercepta requisicoes antes do controller |

---

## Como adicionar um novo recurso

1. Crie o **Model** em `src/models/`
2. Crie a **Interface do Repository** em `src/data/IRepository/`
3. Crie o **Repository** em `src/data/Repository/`
4. Crie a **Interface do Service** em `src/service/IService/`
5. Crie o **Service** em `src/service/`
6. Crie o **Controller** em `src/controllers/`
7. Registre as dependencias em `src/config/dependencies.php`
8. Adicione as rotas em `src/routes/web.php`
9. Adicione as annotations do Swagger no controller

---

## Licenca

MIT
