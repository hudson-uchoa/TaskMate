<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Core\TokenHandlerInterface;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;

class AuthService
{
    private UserRepository $userRepo;
    private TokenHandlerInterface $tokenHandler;

    public function __construct(UserRepository $userRepo, TokenHandlerInterface $tokenHandler)
    {
        $this->userRepo = $userRepo;
        $this->tokenHandler = $tokenHandler;
    }

    public function register(RegisterRequest $request): ?string
    {
        if ($this->userRepo->findByEmail($request->getEmail())) {
            return null;
        }
    
        $hashedPassword = password_hash($request->getPassword(), PASSWORD_DEFAULT);

        $user = new User(
            null,
            $request->getName(),
            $request->getEmail(),
            $hashedPassword,
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        );

        $user = $this->userRepo->create($user);
    
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'iat' => time(),
            'exp' => time() + 3600,
        ];
    
        return $this->tokenHandler->generateToken($payload);
    }
    
    public function login(LoginRequest $request): ?string
    {
        $user = $this->userRepo->findByEmail($request->getEmail());
    
        if (!$user || !password_verify($request->getPassword(), $user->getPassword())) {
            return null;
        }
    
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'iat' => time(),
            'exp' => time() + 3600,
        ];
    
        return $this->tokenHandler->generateToken($payload);
    }
}
