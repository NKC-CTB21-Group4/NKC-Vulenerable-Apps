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
    $postId = (int) $this->resolveArg("postId");
    try {
      //例外を返す可能性がある
      $post = $this->postRepository->findPostOfId($postId);
  } catch (PostNotFoundException $e) {
      $this->logger->info("post with id `$postId` not found.");
      return $this->respondWithData("Post Not Found.", 404);
  }
    $this->logger->info("post of `$postId` was viewed.");
    return $this->respondWithData($post);
  }
}
