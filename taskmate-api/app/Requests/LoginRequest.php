<?php

namespace App\Requests;

class LoginRequest
{
    private string $email;
    private string $password;

    public function __construct(array $data)
    {
        $this->email    = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';

        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->email)) {
            throw new \InvalidArgumentException('E-mail é obrigatório');
        }
    
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido');
        }
    
        if (empty($this->password)) {
            throw new \InvalidArgumentException('Senha é obrigatória');
        }
    }

    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
}
