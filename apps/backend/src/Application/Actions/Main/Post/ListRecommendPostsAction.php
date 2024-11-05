<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;

class ListRecommendPostsAction extends PostAction
{
  protected function action(): Response
  {
    //todo tokenの有無でアルゴリズムが変わるようにする
    $posts = $this->postRepository->findAll();

    $this->logger->info("Post List was viewed.");

    return $this->respondWithData($posts);
  }
}