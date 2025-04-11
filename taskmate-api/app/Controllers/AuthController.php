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
                    'message' => '"E-mail ou senha incorretos. Verifique suas credenciais e tente novamente.',
                    'token' => null,
                ]);
            }

            return $this->jsonResponse($response, 200, [
                'message' => 'Login efetuado com sucesso!',
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
                'message' => 'Não foi possível realizar o login no momento. Tente novamente em instantes.',
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
                    'message' => 'Já existe uma conta cadastrada com este e-mail.',
                ]);
            }

            return $this->jsonResponse($response, 201, [
                'success' => true,
                'message' => 'Cadastro concluído com sucesso! Seja bem-vindo!',
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
                'message' => 'Não conseguimos concluir o cadastro. Tente novamente mais tarde.',
            ]);
        }
    }

    public function logout(Request $request, Response $response): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->jsonResponse($response, 400, [
                'success' => false,
                'message' => 'Token de autenticação não fornecido.',
            ]);
        }

        $token = substr($authHeader, 7);

        try {
            $this->authService->logout($token);

            return $this->jsonResponse($response, 200, [
                'success' => true,
                'message' => 'Logout efetuado com sucesso. Até logo!',
            ]);
        } catch (\Throwable $e) {
            return $this->jsonResponse($response, 500, [
                'success' => false,
                'message' => 'Ocorreu um erro ao finalizar sua sessão. Tente novamente.',
            ]);
        }
    }
}
