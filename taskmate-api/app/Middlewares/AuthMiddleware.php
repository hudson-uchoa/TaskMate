<?php

namespace App\Middlewares;

use App\Core\TokenBlacklistInterface;
use App\Core\TokenHandlerInterface;
use App\Repositories\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response as SlimResponse;

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
            return $this->unauthorizedResponse();
        }

        $token = substr($authHeader, 7);

        try {
            if ($this->blacklist->isBlacklisted($token)) {
                return $this->unauthorizedResponse('Token expirado ou revogado.');
            }
            
            $payload = $this->tokenHandler->validateToken($token);
            $user = $this->userRepo->findById($payload->sub);

            if (!$user) {
                return $this->unauthorizedResponse();
            }

            $request = $request->withAttribute('user', $user);

            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->unauthorizedResponse($e->getMessage());
        }
    }

    private function unauthorizedResponse(string $message = 'Token inválido ou ausente.'): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}
