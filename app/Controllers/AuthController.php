<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Traits\JsonResponseTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    use JsonResponseTrait;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            $loginRequest = new LoginRequest($data);
            $token = $this->authService->login($loginRequest);

            if (!$token) {
                return $this->jsonResponse($response, 401, [
                    'message' => 'Credenciais inválidas.',
                    'token' => null,
                ]);
            }

            return $this->jsonResponse($response, 200, [
                'message' => 'Login realizado com sucesso.',
                'token' => $token,
            ]);

        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro interno ao fazer login: '.$e->getMessage(),
            ]);
        }
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            $registerRequest = new RegisterRequest($data);
            $token = $this->authService->register($registerRequest);

            if (!$token) {
                return $this->jsonResponse($response, 400, [
                    'success' => false,
                    'message' => 'Usuário já existe.',
                ]);
            }

            return $this->jsonResponse($response, 201, [
                'success' => true,
                'message' => 'Registro realizado com sucesso.',
                'token' => $token,
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Erro interno ao registrar: '.$e->getMessage(),
            ]);
        }
    }
}
