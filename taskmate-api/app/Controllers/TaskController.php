<?php

namespace App\Controllers;

use App\Requests\CreateTaskRequest;
use App\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Traits\JsonResponseTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;

class TaskController
{
    use JsonResponseTrait;
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        
        /** @var \App\Models\User|null $user*/
        $userId = $user ? $user->getId() : 0;

        if (empty($data) || !is_array($data)) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => 'Dados do body não enviados ou inválidos.',
                'task' => null
            ]);
        }

        try {
            $taskRequest = new CreateTaskRequest($data, $userId);
            $task = $this->taskService->createTask($taskRequest);

            return $this->jsonResponse($response, 201, [
                'success' => true,
                'message' => 'Task criada com sucesso!',
                'task' => $task
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => $e->getMessage(),
                'task' => null
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro interno do servidor.',
                'task' => null
            ]);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $taskId = (int)($args['id'] ?? 0);
        $user = $request->getAttribute('user');
        
        /** @var \App\Models\User|null $user*/
        $userId = $user ? $user->getId() : 0;

        if (empty($data) || !is_array($data)) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => 'Dados do body não enviados ou inválidos.',
                'task' => null
            ]);
        }

        try {
            $data['id'] = $taskId;
            $taskRequest = new UpdateTaskRequest($data, $userId);
            $success = $this->taskService->updateTask($taskRequest);

            return $this->jsonResponse($response, $success ? 200 : 404, [
                'success' => $success,
                'message' => $success ? 'Task atualizada com sucesso!' : 'Task não encontrada.',
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro interno do servidor. '.$e->getMessage(),
            ]);
        }
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $taskId = (int)($args['id'] ?? 0);
        $user = $request->getAttribute('user');
        
        /** @var \App\Models\User|null $user*/
        $userId = $user ? $user->getId() : 0;

        try {
            $success = $this->taskService->deleteTask($taskId, $userId);

            return $this->jsonResponse($response, $success ? 200 : 404, [
                'success' => $success,
                'message' => $success ? 'Task removida com sucesso!' : 'Task não encontrada.',
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro ao excluir task.',
            ]);
        }
    }

    public function getById(Request $request, Response $response, array $args): Response
    {
        $taskId = (int)($args['id'] ?? 0);
        $user = $request->getAttribute('user');
        
        /** @var \App\Models\User|null $user*/
        $userId = $user ? $user->getId() : 0;

        try {
            $task = $this->taskService->getTaskById($taskId, $userId);

            if (!$task) {
                return $this->jsonResponse($response, 404, [
                    'success' => false,
                    'message' => 'Task não encontrada.',
                    'task' => null
                ]);
            }

            return $this->jsonResponse($response, 200, [
                'success' => true,
                'message' => 'Task encontrada com sucesso!',
                'task' => $task->toArray()
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro ao buscar task.',
                'task' => null
            ]);
        }
    }

    public function getAll(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');
        
        /** @var \App\Models\User|null $user*/
        $userId = $user ? $user->getId() : 0;

        try {
            $tasks = $this->taskService->getAllTasksForUser($userId);

            return $this->jsonResponse($response, 200, [
                'success' => true,
                'message' => 'Busca por tasks realizada com sucesso!',
                'tasks' => array_map(fn ($task) => $task->jsonSerialize(), $tasks) 
            ]);

        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro ao buscar tasks. '.$e->getMessage(),
                'tasks' => []
            ]);
        }
    }
}
