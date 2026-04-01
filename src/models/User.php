<?php

namespace Src\models;

class User
{
    private ?int $Id;
    private string $Nome;
    private string $Email;
    private string $Senha;

    public function __construct(string $nome, string $email, string $senha, ?int $id = null)
    {
        $this->Id = $id;
        $this->Nome = $nome;
        $this->Email = $email;
        $this->Senha = $senha;
    }

     public function getId(): ?int
    {
        return $this->Id;
    }
    public function getNome(): string
    {
        return $this->Nome;
    }

    public function getEmail(): string
    {
        return $this->Email;
    }

    public function getSenha(): string
    {
        
        return $this->Senha;
    }

    public function setId(int $id): void
    {
        $this->Id = $id;
    }
    public function setNome(string $nome): void
    {
        $nome =  preg_replace("/[\'\"<>]/", '', $nome );
        $this->Nome = $nome;
    }

    public function setEmail(string $email): void
    {
        $email = preg_replace("/[^a-zA-Z0-9@._\-]/", '', $email);
        $this->Email = $email;
    }

    public function setSenha(string $senha): void
    {
        $senha =  preg_replace("/[\'\"<>]/", '', $senha );
        $this->Senha = $senha;
    }

    public function AtualizaSenha(string $novaSenha): void
    {
        $novaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
        $this->Senha = $novaSenha;
    }
    public function VerificaSenha(string $senha): bool
    {
       if(str_starts_with($this->Senha, '$2y$') ){
        return password_verify($senha, $this->Senha);
       }

        return $senha === $this->Senha;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->Id,
            'nome' => $this->Nome,
            'email' => $this->Email,
        ];
    }
}