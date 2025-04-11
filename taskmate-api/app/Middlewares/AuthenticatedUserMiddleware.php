<?php

namespace App\Middlewares;

use App\Repositories\UserRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use App\Helpers\JsonResponseHelper;

class AuthenticatedUserMiddleware implements MiddlewareInterface
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function process(Request $request, Handler $handler): Response
    {
        $payload = $request->getAttribute('token_payload');

        if (!$payload || !isset($payload->sub)) {
            return JsonResponseHelper::unauthorized('Token de autenticação inválido ou expirado.');
        }

        $user = $this->userRepo->findById($payload->sub);

        if (!$user) {
            return JsonResponseHelper::unauthorized('Usuário associado ao token não foi encontrado.');
        }

        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
}
