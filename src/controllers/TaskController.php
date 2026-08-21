<?php

namespace App\Controllers;

use App\Repositories\TaskRepository;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TaskController
{
    public function __construct(
        private TaskRepository $taskRepository
    ) {
    }

    public function getUserTasks(
        Request $request,
        Response $response,
        array $args
    ): Response {
        try {
            $userId = (int) $args['id'];

            if (!$this->taskRepository->userExists($userId)) {
                return $this->json($response, [
                    'error' => 'Пользователь не найден'
                ], 404);
            }

            $tasks = $this->taskRepository->getTasksByUserId($userId);

            return $this->json($response, [
                'user_id' => $userId,
                'tasks' => $tasks,
                'count' => count($tasks)
            ]);

        } catch (PDOException $e) {
            error_log($e->getMessage());

            return $this->json($response, [
                'error' => 'Ошибка при получении задач'
            ], 500);
        }
    }

    public function createTask(
        Request $request,
        Response $response
    ): Response {
        try {
            $data = $request->getParsedBody();

            $userId = (int) ($data['user_id'] ?? 0);
            $title = trim($data['title'] ?? '');
            $description = trim($data['description'] ?? '');

            if ($userId <= 0 || $title === '') {
                return $this->json($response, [
                    'error' => 'user_id и title обязательны'
                ], 400);
            }

            if (!$this->taskRepository->userExists($userId)) {
                return $this->json($response, [
                    'error' => 'Пользователь не найден'
                ], 404);
            }

            $task = $this->taskRepository->createTask(
                $userId,
                $title,
                $description
            );

            return $this->json($response, [
                'message' => 'Задача создана',
                'task' => $task
            ], 201);

        } catch (PDOException $e) {
            error_log($e->getMessage());

            return $this->json($response, [
                'error' => 'Ошибка при создании задачи'
            ], 500);
        }
    }

    private function json(
        Response $response,
        array $data,
        int $status = 200
    ): Response {
        $response->getBody()->write(json_encode($data));

        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}