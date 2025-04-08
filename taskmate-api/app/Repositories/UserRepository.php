<?php

namespace App\Repositories;

use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(User $user): User
    {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO users (name, email, password, created_at, updated_at) 
                VALUES (:name, :email, :password, :createdAt, :updatedAt)
            ');
    
            $stmt->execute($user->toArray(['id']));
    
            $user->setId((int) $this->pdo->lastInsertId());
    
            return $user;
        } catch (\PDOException $e){
            throw new \RuntimeException('Erro ao criar usuário: ' . $e->getMessage(), 0);
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $row ? $this->hydrate($row) : null;
        } catch (\PDOException $e){
            throw new \RuntimeException('Erro ao buscar usuário pelo email: '.$e->getMessage(), 0);
        }
    }

    public function findById(int $id): ?User
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $row ? $this->hydrate($row) : null;
        } catch (\PDOException $e){
            throw new \RuntimeException('Erro ao buscar usuário pelo id: '.$e->getMessage(), 0);
        }
    }

    public function update(User $user): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE users SET name = :name, email = :email, updatedAt = :updatedAt
                WHERE id = :id'
            );
    
            $stmt->execute($user->toArray());
            
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e){
            throw new \RuntimeException('Erro ao atualizar usuário: '.$e->getMessage(), 0);
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
    
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e){
            throw new \RuntimeException('Erro ao deletar usuário: '.$e->getMessage(), 0);
        }
    }

    private function hydrate(array $data): User
    {
        return User::fromArray($data);
    }
}
