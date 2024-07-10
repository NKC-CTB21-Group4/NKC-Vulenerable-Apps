<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Tag;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Tag\Tag;
use App\Domain\Main\Tag\TagNotFoundException;

class RemoveTagAction extends TagAction
{
  protected function action(): Response
  {
    $tagId = (int) $this->resolveArg("tagId");
    
    try {
      $this->tagRepository->remove($tagId);
      $this->logger->info("Tag of id {$tagId} was removed successfully.");
    } catch (TagNotFoundException $e) {
      $this->logger->error("Tag removal failed: " . $e->getMessage());
      return $this->respondWithData('Tag not found', 404);
    }

    return $this->respondWithData(['message' => 'Tag removed successfully'], 200);
  }
}