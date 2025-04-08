<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Requests\CreateTaskRequest;
use App\Requests\UpdateTaskRequest;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepository = $taskRepo;
    }

    public function createTask(CreateTaskRequest $request): Task
    {
        $task = new Task(
            null,
            $request->getTitle(),
            $request->getUserId(),
            $request->getDescription(),
            $request->getStatus(),
            $request->getPriority(),
            $request->getDueDate(),
            $request->getCategory(),
            $request->getCompletedAt(),
            $request->getReminderAt(),
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        );

        return $this->taskRepository->create($task);
    }

    public function updateTask(UpdateTaskRequest $request): bool
    {
        $existingTask = $this->taskRepository->findById($request->getId());

        if (!$existingTask) {
            throw new \RuntimeException('Task não encontrada.');
        }

        if($existingTask->getUserId() !== $request->getUserId()){
            throw new \RuntimeException('Acesso negado.');
        }

        $existingTask->setTitle($request->getTitle());
        $existingTask->setDescription($request->getDescription());
        $existingTask->setStatus($request->getStatus());
        $existingTask->setPriority($request->getPriority());
        $existingTask->setDueDate($request->getDueDate());
        $existingTask->setCategory($request->getCategory());
        $existingTask->setReminderAt($request->getReminderAt());
        $existingTask->setCompletedAt($request->getCompletedAt());
        $existingTask->setUpdatedAt((new \DateTimeImmutable())->format('Y-m-d H:i:s'));

        return $this->taskRepository->update($existingTask);
    }

    public function deleteTask(int $taskId, int $userId): bool
    {
        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            throw new \RuntimeException('Task não encontrada.');
        }

        if($task->getUserId() !== $userId){
            throw new \RuntimeException('Acesso negado.');
        }

        return $this->taskRepository->delete($taskId);
    }

    public function getTaskById(int $id, int $userId): ?Task
    {
        $task = $this->taskRepository->findById($id);

        if (!$task || $task->getUserId() !== $userId) {
            return null;
        }

        return $task;
    }

    public function getAllTasksForUser(int $userId): array
    {
        return $this->taskRepository->findAllByUserId($userId);
    }
}
