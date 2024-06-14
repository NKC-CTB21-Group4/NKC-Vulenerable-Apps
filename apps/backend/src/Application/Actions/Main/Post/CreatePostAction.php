<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;

class CreatePostAction extends PostAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $user = $this->checkUserAuthorization($user);
    if ($user === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    $data = $this->getFormData();

    $invalidResponse = $this->validateInputData($data);
    if($invalidResponse !== null){
      return $invalidResponse;
    }

    $post = new Post($user,$data['content']);

    $this->postRepository->create($post);

    $this->logger->info("Post created successfully");

    return $this->respondWithData($post, 201);    

  }

  private function validateInputData(array $data): ?Response
  {
      if (empty($data['content'])) {
        $this->logger->info("Post creation failed due to invalid input");
        return $this->respondWithData('Invalid input', 400);
    }
    return null;
  }
}