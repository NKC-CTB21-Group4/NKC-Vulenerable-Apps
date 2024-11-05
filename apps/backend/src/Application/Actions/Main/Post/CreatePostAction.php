<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;
use Ramsey\Uuid\Uuid;
use Slim\Psr7\UploadedFile;

class CreatePostAction extends PostAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $user = $this->checkUserAuthorization($user);
    if ($user === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    //コンテンツ(文字)のチェック
    $data = $this->getFormData();

    $invalidResponse = $this->validateInputData($data);
    if($invalidResponse !== null){
      return $invalidResponse;
    }

    //画像ファイルがあれば
    $uploadedFiles = $this->request->getUploadedFiles();
    $image = $uploadedFiles['image'] ?? null;

    if ($image && $image->getError() === UPLOAD_ERR_OK) {
        $filename = $this->moveUploadedFile($image);
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = '/posts' . '/' . $filename;
    } else {
        $filename = null;
    }

    $post = new Post($user,$data['content'],$filename);

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

  private function moveUploadedFile(UploadedFile $uploadedFile): string 
  {
    $directory = '/var/www/assets/content';
    $uuid = Uuid::uuid4();
    $uuidWithoutHyphens = str_replace('-', '', $uuid->toString());

    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $filename = sprintf('%s.%s', $uuidWithoutHyphens, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
  }
}