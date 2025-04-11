<?php

namespace App\Models;

use App\Traits\ArrayConvertible;

class Task implements \JsonSerializable
{

    use ArrayConvertible;
    private ?int $id;
    private string $title;
    private int $userId;
    private ?string $description;
    private string $status;
    private string $priority;
    private ?string $dueDate;
    private ?string $category;
    private ?string $reminderAt;
    private ?string $completedAt;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        ?int $id,
        string $title,
        int $userId,
        ?string $description,
        string $status,
        string $priority,
        ?string $dueDate,
        ?string $category,
        ?string $completedAt,
        ?string $reminderAt,
        string $createdAt,
        string $updatedAt
    ) {
        $this->id          = $id;
        $this->title       = $title;
        $this->userId      = $userId;
        $this->description = $description;
        $this->status      = $status;
        $this->priority    = $priority;
        $this->dueDate     = $dueDate;
        $this->category    = $category;
        $this->reminderAt  = $reminderAt;
        $this->completedAt = $completedAt;
        $this->createdAt   = $createdAt;
        $this->updatedAt   = $updatedAt;
    }

    protected function fields(): array
    {
        return [
            'id',
            'title',
            'userId',
            'description',
            'status',
            'priority',
            'dueDate',
            'category',
            'reminderAt',
            'completedAt',
            'createdAt',
            'updatedAt',
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'],
            $data['user_id'],
            $data['description'],
            $data['status'],
            $data['priority'],
            $data['due_date'],
            $data['category'],
            $data['completed_at'],
            $data['reminder_at'],
            $data['created_at'],
            $data['updated_at'],
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'status' => $this->getStatus(),
            'priority' => $this->getPriority(),
            'due_date' => $this->getDueDate(),
            'category' => $this->getCategory(),
            'reminder_at' => $this->getReminderAt(),
            'completed_at' => $this->getCompletedAt(),
            'user_id' => $this->getUserId(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getUserId(): int { return $this->userId; }
    public function getDescription(): ?string { return $this->description; }
    public function getStatus(): string { return $this->status; }
    public function getPriority(): string { return $this->priority; }
    public function getDueDate(): ?string { return $this->dueDate; }
    public function getCategory(): ?string { return $this->category; }
    public function getReminderAt(): ?string { return $this->reminderAt; }
    public function getCompletedAt(): ?string { return $this->completedAt; }
    public function getCreatedAt(): \DateTime { return new \DateTime($this->createdAt); }
    public function getUpdatedAt(): \DateTime { return new \DateTime($this->createdAt); }

    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setUserId(int $id): void { $this->userId = $id; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setPriority(string $priority): void { $this->priority = $priority; }
    public function setDueDate(?string $dueDate): void { $this->dueDate = $dueDate; }
    public function setCategory(?string $category): void { $this->category = $category; }
    public function setReminderAt(?string $reminderAt): void { $this->reminderAt = $reminderAt; }
    public function setCompletedAt(?string $completedAt): void { $this->completedAt = $completedAt; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(string $updatedAt): void { $this->updatedAt = $updatedAt; }
}
