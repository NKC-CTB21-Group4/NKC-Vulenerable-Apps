<?php

declare(strict_types=1);

namespace App\Application\Middleware\Main;

use App\Infrastructure\Persistence\Main\Auth\JwtService;
use App\Domain\Main\User\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;
use Psr\Log\LoggerInterface;

class AdminJwtMiddleware implements MiddlewareInterface {
    private JwtService $jwtService;
    private UserRepository $userRepository;
    private LoggerInterface $logger;

    public function __construct(JwtService $jwtService, LoggerInterface $logger,UserRepository $userRepository) {
        $this->jwtService = $jwtService;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
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
        
        try {
          //例外を返す可能性がある
          $user = $this->userRepository->findUserOfId($decoded["user"]->id);
        } catch (UserNotFoundException $e) {
            $this->logger->info("user with id `$id` not found.");
            return $this->respondWithData("User Not Found.", 404);
        }
        if(!$user->getIsAdmin()){
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Access Denied. You do not have the necessary permissions to perform this action.']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }


        // トークンが有効であれば、リクエストにデコードされたペイロードを追加します
        $request = $request->withAttribute('token', $user);
        return $handler->handle($request);
    }
}
