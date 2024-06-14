<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;

class DeletePostAction extends PostAction
{
  protected function action():Response
  {
    $user = $this->getUserFromToken();
    
    $user = $this->checkUserAuthorization($user);
    if ($user === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    $postId = (int) $this->resolveArg("postId");
    
    try{
      $this->postRepository->delete($postId);
    }catch(PostNotFoundException $e) {
      $this->logger->info("post with id `$postId` not found.");
      return $this->respondWithData("Post Not Found.", 404);
    }

    $this->logger->info("Post of id `${postId}` was deleted by user with id `${postId}`.");

    return $this->respondWithData(['message' => 'Post deleted successfully']);
  }

}