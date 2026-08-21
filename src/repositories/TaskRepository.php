<?php

namespace App\Repositories;

use PDO;

class TaskRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function userExists(int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM users WHERE id = :user_id'
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function getTasksByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, title, description, user_id, created_at
             FROM tasks
             WHERE user_id = :user_id
             ORDER BY created_at DESC'
        );

        $stmt->execute([
            'user_id' => $userId
        ]);

        return $stmt->fetchAll();
    }

    public function createTask(
        int $userId,
        string $title,
        string $description
    ): array {
        $stmt = $this->pdo->prepare(
            'INSERT INTO tasks (user_id, title, description)
             VALUES (:user_id, :title, :description)'
        );

        $stmt->execute([
            'user_id' => $userId,
            'title' => $title,
            'description' => $description
        ]);

        $taskId = (int) $this->pdo->lastInsertId();

        return $this->getTaskById($taskId);
    }

    private function getTaskById(int $taskId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, title, description, user_id, created_at
             FROM tasks
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $taskId
        ]);

        return $stmt->fetch();
    }
}