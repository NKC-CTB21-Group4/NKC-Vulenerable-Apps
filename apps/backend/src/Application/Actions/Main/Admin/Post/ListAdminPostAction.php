<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Admin\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;

class ListAdminPostAction extends AdminPostAction
{
  protected function action(): Response
  {
    $posts = $this->postRepository->findAll();

    $this->logger->info("Post List was viewed.");

    return $this->respondWithData($posts);  
  }
}