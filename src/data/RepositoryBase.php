<?php

namespace Src\data;

use JsonException;
use PDO;
use Src\core\Http\JsonResponse;
use Src\core\Logger;

class RepositoryBase
{
    private $conexao;


    public function __construct()
    {
        $this->GetConnection();
    }
    public function GetConnection()
    {
        try {
            $conn_string = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'];
            $this->conexao = new PDO($conn_string, $_ENV['DB_USER'], $_ENV['DB_PASS']);
        } catch (\Throwable $error) {
            Logger::error('Erro ao conectar ao banco de dados: ' . $error->getMessage());
            throw new \Exception('Erro ao conectar ao banco de dados: ' . $error->getMessage());
        }

        return $this->conexao;
    }
}
