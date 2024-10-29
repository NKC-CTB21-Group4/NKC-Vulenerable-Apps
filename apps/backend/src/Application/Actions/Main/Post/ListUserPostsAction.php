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

    // tokenからuserId特定->FollowerId特定
    $userFromToken = $this->getUserFromToken();
    $authenticatedUser = $this->checkUserAuthorization($userFromToken);
    if ($authenticatedUser === null) {
        return $this->respondWithData('Unauthorized', 403);
    }

    $followerId = $authenticatedUser->getId();

    // userIdからそのuserが鍵あかか判断
    $user = $this->userRepository->findUserOfId($userId);
    $isPrivate = $user->getIsPrivate();

    $bothFollowCheck = $this->followRepository->bothFollowChecker($followerId, $userId);
    if ($isPrivate && !$bothFollowCheck) {
        return $this->respondWithData("This user is Private and you are not mutual followers.", 403);
    }

    try {
      // 例外を返す可能性がある
      $posts = $this->postRepository->findPostOfUser($user);
    } catch (PostNotFoundException $e) {
        $this->logger->info("Post with id `$postId` not found.");
        return $this->respondWithData("Post Not Found.", 404);
  }

  if ($posts == null) {
      $this->logger->info("This userId '$userId' is Private.");
      return $this->respondWithData("This user is Private.", 405);
  }
    
    $username = $user->getUsername();

    $this->logger->info("${username}User's Post list was viewed.");

    return $this->respondWithData($posts);
  }

}