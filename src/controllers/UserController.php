<?php

namespace App\Controllers;

use PDO;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $name = trim($data['name'] ?? '');

        if ($email === '' || $password === '' || $name === '') {
            return $this->json($response, [
                'error' => 'Email, password и name обязательны'
            ], 400);
        }

        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            return $this->json($response, [
                'error' => 'Некорректный email'
            ], 400);
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (name, email, password)
                 VALUES (:name, :email, :password)'
            );

            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash
            ]);

            return $this->json($response, [
                'message' => 'Пользователь зарегистрирован',
                'user' => [
                    'id' => (int) $this->pdo->lastInsertId(),
                    'name' => $name,
                    'email' => $email
                ]
            ], 201);

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return $this->json($response, [
                    'error' => 'Пользователь с таким email уже существует'
                ], 409);
            }

            error_log($e->getMessage());

            return $this->json($response, [
                'error' => 'Ошибка базы данных'
            ], 500);
        }
    }

    private function json(
        Response $response,
        array $data,
        int $status
    ): Response {
        $response->getBody()->write(json_encode($data));

        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}