<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Reaction;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;

class GetFavsCountByPost extends ReactionAction
{
  protected function action(): Response
  {
    $postId = (int) $this->resolveArg("postId");

    $user = $this->getUserFromHeader();

    try {
      //例外を返す可能性がある
      $post = $this->postRepository->findPostOfId($postId);
    } catch (PostNotFoundException $e) {
      $this->logger->info("post with id `$postId` not found.");
      return $this->respondWithData("Post Not Found.", 404);
    }

    $result = $this->reactionRepository->getFavsCountByPostId($postId);

    $isClicked = null;
    if($user !== null){
      $isClicked = $this->reactionRepository->findReactionByUserIdAndPostId((int)$user->id,$postId);
    }
    

    return $this->respondWithData(["fav"=>$result,"clicked"=>$isClicked ? $isClicked->isFav() : false]);


  }
}