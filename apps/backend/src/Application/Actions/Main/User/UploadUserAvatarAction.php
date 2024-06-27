<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

class UploadUserAvatarAction extends UserAction
{

    public function action(): Response
    {
        $directory = '/var/www/assets/avatar';
        $user = $this->getUserFromToken();

        $user = $this->checkUserAuthorization($user);
        if ($user === null) {
            return $this->respondWithData('Unauthorized', 403);
        }

        $userId = $user->getId();
        $uploadedFiles = $this->request->getUploadedFiles();
        $avatar = $uploadedFiles['avatar'] ?? null;

        if (!($avatar instanceof UploadedFileInterface) || $avatar->getError() !== UPLOAD_ERR_OK) {
          return $this->respondWithData("Failed to upload avatar", 400);
        }

        $this->moveUploadedFile($directory, $avatar, $userId);
        
        return $this->respondWithData($filename, 200);
    }

    private function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, int $userId)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = sprintf('%s.%s', $userId, $extension);
        $filename = $directory . DIRECTORY_SEPARATOR . $basename;
        $uploadedFile->moveTo($filename);

        return $filename;
    }
}
