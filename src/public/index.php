<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Connections\DatabaseConnection;
use App\Controllers\TaskController;
use App\Controllers\UserController;
use App\Repositories\TaskRepository;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$database = new DatabaseConnection();
$pdo = $database->getConnection();

$taskRepository = new TaskRepository($pdo);

$userController = new UserController($pdo);
$taskController = new TaskController($taskRepository);

$app->post('/api/users', [$userController, 'create']);

$app->get(
    '/api/users/{id}/tasks',
    [$taskController, 'getUserTasks']
);

$app->post(
    '/api/tasks',
    [$taskController, 'createTask']
);

$app->run();