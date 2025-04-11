<?php

namespace App\Models;

use App\Traits\ArrayConvertible;

class User
{
    use ArrayConvertible;

    private ?int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $createdAt;
    private string $updatedAt;

    public function __construct(?int $id, string $name, string $email, string $password, string $createdAt, string $updatedAt)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->email     = $email;
        $this->password  = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    protected function fields(): array
    {
        return [
            'id',
            'name',
            'email',
            'password',
            'createdAt',
            'updatedAt',
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'],
            $data['email'],
            $data['password'],
            $data['created_at'],
            $data['updated_at'],
        );
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): \DateTime { return new \DateTime($this->createdAt); }
    public function getUpdatedAt(): \DateTime { return new \DateTime($this->updatedAt); }
    
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }
    public function setUpdatedAt(string $updatedAt): void { $this->updatedAt = $updatedAt; }
}
