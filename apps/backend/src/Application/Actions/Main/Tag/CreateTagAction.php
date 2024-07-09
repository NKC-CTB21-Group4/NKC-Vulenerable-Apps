<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Tag;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Tag\Tag;
use App\Domain\Main\Tag\TagCreationFailedException;

class CreateTagAction extends TagAction
{
  protected function action(): Response
  {
    $data = $this->getFormData();

    $invalidResponse = $this->validateInputData($data);
    if($invalidResponse !== null){
      return $invalidResponse;
    }

    $tag = new Tag($data['tagname']);
    
    try {
      $this->tagRepository->create($tag);
      $this->logger->info("Tag of id {$tag->getId()} was created successfully.");
    } catch (TagCreationFailedException $e) {
      $this->logger->error("Tag creation failed: " . $e->getMessage());
      return $this->respondWithData('Tag creation failed', 500);
    }

  return $this->respondWithData($tag, 201);
  }

  private function validateInputData(array $data): ?Response
  {
      if (empty($data['tagname'])) {
        $this->logger->info("Tag creation failed due to invalid input");
        return $this->respondWithData('Invalid input', 400);
    }
    return null;
  }
}