<?php

namespace App\Repositories;

use App\Models\Task;
use PDO;

class TaskRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Task $task): Task
    {
        try{
            $stmt = $this->pdo->prepare('
                INSERT INTO tasks (user_id, title, description, completed_at, due_date, category, reminder_at, status, priority, created_at, updated_at)
                VALUES (:userId, :title, :description, :completedAt, :dueDate, :category, :reminderAt, :status, :priority, :createdAt, :updatedAt)
            ');
        
            $stmt->execute($task->toArray(['id']));
    
            
            $task->setId((int) $this->pdo->lastInsertId());
    
            return $task;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erro ao criar task: ' . $e->getMessage(), 0);
        }
    }

    public function findById(int $id): ?Task
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$data) {
                return null;
            }
    
            return $this->hydrate($data);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erro ao buscar task pelo id: ' . $e->getMessage(), 0);
        }
    }

    public function findAllByUserId(int $userId): array
    {
        try{
            $stmt = $this->pdo->prepare('SELECT * FROM tasks WHERE user_id = :user_id ORDER BY id');
            $stmt->execute([':user_id' => $userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return array_map([$this, 'hydrate'], $rows);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erro ao buscar todas as task pelo user id: ' . $e->getMessage(), 0);
        }
    }

    public function update(Task $task): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE tasks 
                SET title = :title, 
                    description = :description, 
                    due_date = :dueDate,
                    reminder_at = :reminderAt,
                    completed_at = :completedAt,
                    status = :status, 
                    priority = :priority,
                    category = :category, 
                    updated_at = :updatedAt
                WHERE id = :id'
            );
    
            $stmt->execute($task->toArray(['userId', 'createdAt']));
    
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erro ao atualizar task: ' . $e->getMessage(), 0);
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM tasks WHERE id = :id');
            $stmt->execute([':id' => $id]);
    
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erro ao deletar task: ' . $e->getMessage(), 0);
        }
    }

    private function hydrate(array $data): Task
    {
        return Task::fromArray($data);
    }    
}
