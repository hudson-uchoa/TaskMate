<?php

namespace App\Requests;

class CreateTaskRequest
{
    private string $title;
    private int $userId;
    private ?string $description;
    private string $status;
    private string $priority;
    private ?string $dueDate;
    private ?string $category;
    private ?string $reminderAt;
    private ?string $completedAt;

    public function __construct(array $data, int $userId)
    {
        $this->title       = $data['title'] ?? '';
        $this->userId      = $userId;
        $this->description = $data['description'] ?? null;
        $this->status      = $data['status'] ?? 'pending';
        $this->priority    = $data['priority'] ?? 'normal';
        $this->dueDate     = $data['due_date'] ?? null;
        $this->category    = $data['category'] ?? null;
        $this->reminderAt  = $data['reminder_at'] ?? null;
        $this->completedAt = $data['completed_at'] ?? null;

        $this->validate();
    }

    private function validate(): void
    {
        if (!$this->userId) {
            throw new \InvalidArgumentException('Usuário não identificado.');
        }

        if (empty($this->title)) {
            throw new \InvalidArgumentException('Título é obrigatório.');
        }

        if (!in_array($this->status, ['pending', 'in_progress', 'completed'])) {
            throw new \InvalidArgumentException('Status inválido.');
        }

        if (!in_array($this->priority, ['low', 'normal', 'high'])) {
            throw new \InvalidArgumentException('Prioridade inválida.');
        }

        $this->validateDate($this->dueDate, 'Data de vencimento inválida.');
        $this->validateDate($this->reminderAt, 'Data de lembrete inválida.');
        $this->validateDate($this->completedAt, 'Data de conclusão inválida.');
    }

    private function validateDate(?string $date, string $errorMessage): void
    {
        if ($date === null) {
            return;
        }

        $d = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
        if (!$d || $d->format('Y-m-d H:i:s') !== $date) {
            throw new \InvalidArgumentException($errorMessage);
        }
    }

    public function getTitle(): string { return $this->title; }
    public function getUserId(): int { return $this->userId; }
    public function getDescription(): ?string { return $this->description; }
    public function getStatus(): string { return $this->status; }
    public function getPriority(): string { return $this->priority; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getCategory(): ?string { return $this->category; }
    public function getReminderAt(): ?string { return $this->reminderAt; }
    public function getCompletedAt(): ?string { return $this->completedAt; }
}
