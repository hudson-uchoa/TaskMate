<?php

namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;

trait JsonResponseTrait
{
    protected function jsonResponse(Response $response, int $statusCode, $data): Response
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
