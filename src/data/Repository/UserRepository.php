<?php

namespace Src\data\Repository;

use DateTime;
use Src\data\IRepository\IUserRepository;
use Src\data\RepositoryBase;
use Src\models\User;
use Exception;
use PDO;


class UserRepository implements IUserRepository
{
    private PDO $_conn;

    

    public function __construct(RepositoryBase $conn)
    {
        $this->_conn = $conn->GetConnection();
    }

    // public function CreateUser(User $newUser): User {}
        public function UpdateUser(User $user): User
    {
        try {
            $sql = "UPDATE usuario_tb SET nome = :name, email = :email, senha = :password WHERE id = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindValue(':name', $user->getNome());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':password', $user->getSenha());
            $stmt->bindValue(':id', $user->getId());
            $stmt->execute();


            return $user;
        } catch (\Throwable $e) {
            throw new Exception('Erro ao atualizar o usuario no banco de dados:' . $e->getMessage());
        }
    }
    // public function DeleteUser(int $id): bool {}
    public function GetAllUser(): array
    {
        try {
            $sql = "SELECT * FROM usuario_tb";
            $stmt = $this->_conn->prepare($sql);
            $stmt->execute();
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = [];

            foreach ($usersData as $row) {
                $users[] = new User($row['nome'], $row['email'], $row['senha'], $row['id']);
            }
            return $users;
        } catch (\Throwable $e) {
            throw new Exception('Erro ao buscar todos os usuarios: ' . $e->getMessage());
        }
    }
    public function GetUserById(int $id): ?User
    {
        try {
            $sql = "SELECT * FROM usuario_tb WHERE id = :id";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? new User($user['nome'], $user['email'], $user['senha'], $user['id']) : null;
        } catch (\Throwable $e) {
            throw new Exception('Erro ao pegar usuário no banco pelo ID' . $e->getMessage());
        }
    }

    public function GetUserByEmail(string $email): ?User
    {
        try {
            $sql = "SELECT * FROM usuario_tb WHERE email = :email";
            $stmt = $this->_conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? new User($user['nome'], $user['email'], $user['senha'], $user['id']) : null;
        } catch (\Throwable $e) {
            throw new Exception('Erro ao pegar o usuario no banco de dados pelo EMAIL:' . $e->getMessage());
        }
    }
}
