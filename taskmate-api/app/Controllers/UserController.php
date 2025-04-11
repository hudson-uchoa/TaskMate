<?php

namespace App\Controllers;

use App\Traits\JsonResponseTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    use JsonResponseTrait;
    public function me(Request $request, Response $response): Response
    {
        /** @var \App\Models\User $user*/
        $user = $request->getAttribute('user');

        $payload = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
        ];

        return $this->jsonResponse($response, 200, [
            'success' => true,
            'message' => 'Dados do usuário retornados com sucesso!',
            'user' => $payload
        ]);
    }
}
