<?php

declare(strict_types=1);

namespace App\Application\Middleware\Main;

use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use Psr\Log\LoggerInterface;

class JwtMiddleware implements MiddlewareInterface {
    private JwtService $jwtService;
    private LoggerInterface $logger;

    public function __construct(JwtService $jwtService, LoggerInterface $logger) {
        $this->jwtService = $jwtService;
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Token not provided']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $token = $matches[1];
        $decoded = $this->jwtService->validateToken($token);

        if (!$decoded) {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Invalid token']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // トークンが有効であれば、リクエストにデコードされたペイロードを追加します
        $request = $request->withAttribute('token', $decoded);
        return $handler->handle($request);
    }
}
