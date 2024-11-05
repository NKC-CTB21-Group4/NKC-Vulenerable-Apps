<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Reaction;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;

class HandleFavoriteAction extends ReactionAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $user = $this->userRepository->findUserOfId($user->id);

    $postId = (int) $this->resolveArg("postId");

    try {
      //例外を返す可能性がある
      $post = $this->postRepository->findPostOfId($postId);
    } catch (PostNotFoundException $e) {
      $this->logger->info("post with id `$postId` not found.");
      return $this->respondWithData("Post Not Found.", 404);
    }

    $result = $this->reactionRepository->togglePostFav($user,$post);

    return $this->respondWithData($result);


  }
}