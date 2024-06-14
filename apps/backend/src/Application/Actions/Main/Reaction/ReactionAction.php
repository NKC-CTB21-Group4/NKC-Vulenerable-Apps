<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Reaction;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Reaction\ReactionRepository;
use App\Domain\Main\User\User;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ReactionAction extends Action
{
  protected PostRepository $postRepository;
  protected UserRepository $userRepository;
  protected ReactionRepository $reactionRepository;

  public function __construct(
    LoggerInterface $logger,
    PostRepository $postRepository,
    UserRepository $userRepository,
    ReactionRepository $reactionRepository
  ){
    parent::__construct($logger);
    $this->postRepository = $postRepository;
    $this->userRepository = $userRepository;
    $this->reactionRepository = $reactionRepository;
  }

  protected function getUserFromToken():?object
  {
      return (object)$this->request->getAttribute('token')['user'] ?? null;
  }

  protected function checkUserAuthorization(?object $user): ?User
    {
      if($user === null)return null;
        $userId = (int) $this->resolveArg('userId');
        $user = $this->userRepository->findUserOfId($user->id);
        if ($user->getId() !== $userId) {
            return null;
        }
        return $user;
    }

}
