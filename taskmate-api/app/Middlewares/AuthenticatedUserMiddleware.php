<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use App\Core\TokenHandlerInterface;
use App\Repositories\UserRepository;
use Slim\Exception\HttpUnauthorizedException;

class AuthenticatedUserMiddleware implements MiddlewareInterface
{
    private TokenHandlerInterface $tokenHandler;
    private UserRepository $userRepository;

    public function __construct(TokenHandlerInterface $tokenHandler, UserRepository $userRepository)
    {
        $this->tokenHandler = $tokenHandler;
        $this->userRepository = $userRepository;
    }

    public function process(Request $request, Handler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);

        if (!$token) {
            throw new HttpUnauthorizedException($request, 'Token não fornecido');
        }

        $payload = $this->tokenHandler->validateToken($token);

        if (!$payload || !isset($payload->sub)) {
            throw new HttpUnauthorizedException($request, 'Token inválido');
        }

        $user = $this->userRepository->findById($payload->sub);

        if (!$user) {
            throw new HttpUnauthorizedException($request, 'Usuário não encontrado');
        }

        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
}
