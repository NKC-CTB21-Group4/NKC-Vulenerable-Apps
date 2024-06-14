<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\User;
use Psr\Http\Message\ResponseInterface as Response;

abstract class PostAction extends Action
{
  protected PostRepository $postRepository;
  protected UserRepository $userRepository;

  public function __construct(
    LoggerInterface $logger,
    PostRepository $postRepository,
    UserRepository $userRepository
  ){
    parent::__construct($logger);
    $this->postRepository = $postRepository;
    $this->userRepository = $userRepository;
  }

  protected function getUserFromToken():?object
  {
      return (object)$this->request->getAttribute('token')['user'] ?? null;
  }

  protected function checkUserAuthorization(?object $user): ?Response
    {
      if($user === null)return $this->respondWithData('Unauthorized', 403);
        $userId = (int) $this->resolveArg('userId');
        $user = $this->userRepository->findUserOfId($user->id);
        if ($user->getId() !== $userId) {
            return $this->respondWithData('Unauthorized', 403);
        }
        return null;
    }

}
