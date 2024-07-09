<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Tag;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Tag;

class ListTagAction extends TagAction
{
  protected function action(): Response
  {
    //todo tokenの有無でアルゴリズムが変わるようにする
    $posts = $this->tagRepository->findAll();

    $this->logger->info("Tag List was viewed.");

    return $this->respondWithData($posts);
  }
}