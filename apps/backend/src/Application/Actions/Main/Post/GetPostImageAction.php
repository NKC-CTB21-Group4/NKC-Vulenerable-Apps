<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

class GetPostImageAction extends PostAction
{

    public function action(): Response
    {
      $imageId = $this-> resolveArg("imageId");
      $pattern = '/var/www/assets/content/' . $imageId . ".*";
      $files = glob($pattern);

      if (empty($files)) {
        return $this->respondWithData("Image not found", 404);
      }

      $imagePath = $files[0]; // 最初に見つかったファイルを使用する（拡張子は問わない）
      $mimeType = mime_content_type($imagePath);
      $response = $this->response->withHeader('Content-Type', $mimeType);
      $response->getBody()->write(file_get_contents($imagePath));
  
      return $response;
    }
}
