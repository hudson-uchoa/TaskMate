<?php

namespace App\Requests;

class RegisterRequest
{
    private string $name;
    private string $email;
    private string $password;

    public function __construct(array $data)
    {
        $this->name     = $data['name'] ?? '';
        $this->email    = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';

        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Nome é obrigatório');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido');
        }

        if (strlen($this->password) < 6) {
            throw new \InvalidArgumentException('Senha deve ter pelo menos 6 caracteres');
        }
    }

    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
}
