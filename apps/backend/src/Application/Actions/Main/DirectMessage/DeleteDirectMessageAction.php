<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\DirectMessage;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\DirectMessage\DirectMessageNotFoundException;
use App\Domain\Main\DirectMessage\DirectMessageCreationException;
use App\Domain\Main\DirectMessage\DirectMessage;

class DeleteDirectMessageAction extends DirectMessageAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $user= $this->checkUserAuthorization($user,(int)$this->resolveArg('userId'));
    if ($user === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    $messageId = (int)$this->resolveArg('messageId');
    try{
      $this->directMessageRepository->delete($messageId);
    }catch(DirectMessageNotFoundException $e) {
      $this->logger->info("direct-message with id `$messageId` not found.");
      return $this->respondWithData("DirectMessage Not Found.", 404);
    }

    return $this->respondWithData("delete successful",200);
  }
}