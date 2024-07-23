<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;

class ViewPostAction extends PostAction
{
  protected function action(): Response
  {
    //↓は相手側（閲覧したいユーザーと,その人のpost）のid
    $postId = (int) $this->resolveArg("postId");
    $userId = (int) $this->resolveArg("userId");
    //tokenからuserId特定->FollowerId特定
    $user = $this->getUserFromToken();
    $user = $this->checkUserAuthorization($user);

    if ($user === null) {
        return $this->respondWithData('Unauthorized', 403);
    }

    $followerId = $user->getId();

    try {
        //例外を返す可能性がある
        $post = $this->postRepository->findPublicPostOfId($postId, $userId);
    } catch (PostNotFoundException $e) {
        $this->logger->info("post with id `$postId` not found.");
        return $this->respondWithData("Post Not Found.", 404);
    }

    if ($post == null) {
        $this->logger->info("this userId '$userId' is Private");
        return $this->respondWithData("This user is Private.", 405);
    }

    $this->logger->info("post of `$postId` was viewed.");

    return $this->respondWithData($post);
  }
}
