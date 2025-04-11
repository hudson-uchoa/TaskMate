<?php

namespace App\Middlewares;

use App\Core\TokenBlacklistInterface;
use App\Core\TokenHandlerInterface;
use App\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use App\Helpers\JsonResponseHelper;

class AuthMiddleware implements MiddlewareInterface
{
    private TokenHandlerInterface $tokenHandler;
    private UserRepository $userRepo;
    private TokenBlacklistInterface $blacklist;

    public function __construct(
        TokenHandlerInterface $tokenHandler, 
        UserRepository $userRepo,
        TokenBlacklistInterface $blacklist
    ){
        $this->tokenHandler = $tokenHandler;
        $this->userRepo = $userRepo;
        $this->blacklist = $blacklist;
    }

    public function process(Request $request, Handler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return JsonResponseHelper::unauthorized();
        }

        $token = substr($authHeader, 7);

        try {
            if ($this->blacklist->isBlacklisted($token)) {
                return JsonResponseHelper::unauthorized('Token expirado ou revogado.');
            }
            
            $payload = $this->tokenHandler->validateToken($token);

            $request = $request->withAttribute('token_payload', $payload);

            return $handler->handle($request);
        } catch (\Exception $e) {
            return JsonResponseHelper::unauthorized('Falha ao validar o token de autenticação.');
        }
    }
}
