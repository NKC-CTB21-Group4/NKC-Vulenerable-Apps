<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;
use App\Domain\Main\User\UserNotFoundException;


class ListUserPostsAction extends PostAction
{
  protected function action():Response
  {
    $userId = (int)$this->resolveArg('userId');
    
    try {
      $user = $this->userRepository->findUserOfId($userId);
    } catch (UserNotFoundException $e) {
      $this->logger->info("user with id `$userId` not found.");
      return $this->respondWithData("User Not Found.", 404);
    }
    
    $posts = $this->postRepository->findPostOfUser($user);
    $username = $user->getUsername();

    $this->logger->info("${username}User's Post list was viewed.");

    return $this->respondWithData($posts);
  }

}