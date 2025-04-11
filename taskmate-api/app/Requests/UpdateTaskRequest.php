<?php

namespace App\Requests;

class UpdateTaskRequest
{
    private int $id;
    private int $userId;
    private string $title;
    private ?string $description;
    private ?string $dueDate;
    private ?string $category;
    private ?string $status;
    private ?string $priority;
    private ?string $reminderAt;
    private ?string $completedAt;

    public function __construct(array $data, int $userId)
    {
        $this->id           = $data['id'] ?? 0;
        $this->userId       = $userId;
        $this->title        = $data['title'] ?? '';
        $this->description  = $data['description'] ?? null;
        $this->status       = $data['status'] ?? null;
        $this->priority     = $data['priority'] ?? null;
        $this->dueDate      = $data['due_date'] ?? null;
        $this->category      = $data['category'] ?? null;
        $this->priority     = $data['priority'] ?? null;
        $this->reminderAt   = $data['reminder_at'] ?? null;
        $this->completedAt  = $data['completed_at'] ?? null;

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->id <= 0) {
            throw new \InvalidArgumentException('ID da task é obrigatório e deve ser válido.');
        }

        if (!in_array($this->status, ['pending', 'in_progress', 'completed'])) {
            throw new \InvalidArgumentException('Status inválido.');
        }

        if (!in_array($this->priority, ['low', 'normal', 'high'])) {
            throw new \InvalidArgumentException('Prioridade inválida.');
        }

        if (empty($this->title)) {
            throw new \InvalidArgumentException('Título é obrigatório.');
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

        $formats = ['Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d'];
        foreach ($formats as $format) {
            $d = \DateTimeImmutable::createFromFormat($format, $date);
            if ($d && $d->format($format) === $date) {
                return;
            }
        }
        throw new \InvalidArgumentException($errorMessage);
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getCategory(): ?string { return $this->category; }
    public function getStatus(): ?string { return $this->status; }
    public function getPriority(): ?string { return $this->priority; }
    public function getReminderAt(): ?string { return $this->reminderAt; }
    public function getCompletedAt(): ?string { return $this->completedAt; }
}
