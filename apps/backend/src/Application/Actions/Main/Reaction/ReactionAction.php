<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Reaction;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Reaction\ReactionRepository;
use App\Domain\Main\User\User;
use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ReactionAction extends Action
{
  protected PostRepository $postRepository;
  protected UserRepository $userRepository;
  protected ReactionRepository $reactionRepository;
  protected JwtService $jwtService;

  public function __construct(
    LoggerInterface $logger,
    PostRepository $postRepository,
    UserRepository $userRepository,
    ReactionRepository $reactionRepository,
    JwtService $jwtService
  ){
    parent::__construct($logger);
    $this->postRepository = $postRepository;
    $this->userRepository = $userRepository;
    $this->reactionRepository = $reactionRepository;
    $this->jwtService = $jwtService;
  }

  protected function getUserFromToken():?object
  {
      return (object)$this->request->getAttribute('token')['user'] ?? null;
  }

  protected function getUserFromHeader(): ?object
    {
      $authHeader = $this->request->getHeader('Authorization');

      if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
          return null;
      }

      $token = $matches[1];
      $decoded = $this->jwtService->validateToken($token);

      if (!$decoded) {
          return null;
      }
      $user = $decoded['user'];
      return $user;
    }
}
