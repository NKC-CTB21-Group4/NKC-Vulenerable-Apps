<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

class GetPostImageAction extends PostAction
{

    public function action(): Response
    {
      $imageId = $this->resolveArg("imageId");
        $directory = '/var/www/assets/content/';
        $files = scandir($directory);

        $imagePath = null;
        foreach ($files as $file) {
            if (strpos($file, $imageId) === 0) {
                $imagePath = $directory . $file;
                break;
            }
        }

        if ($imagePath === null) {
            return $this->respondWithData("Image not found", 404);
        }

        $mimeType = mime_content_type($imagePath);

        /** @var StreamInterface $body */
        $body = $this->response->getBody();
        $body->write(file_get_contents($imagePath));

        return $this->response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Length', (string)filesize($imagePath))
            ->withBody($body);
    }
}
