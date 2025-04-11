<?php

namespace App\Helpers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

class JsonResponseHelper
{
    public static function unauthorized(string $message = 'Token de autenticação ausente ou malformado.'): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => $message
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }

    public static function success(array $data = [], int $status = 200): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $data
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

}
