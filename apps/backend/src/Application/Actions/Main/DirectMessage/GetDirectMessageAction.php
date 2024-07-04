<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\DirectMessage;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\DirectMessage\DirectMessageCreationException;
use App\Domain\Main\DirectMessage\DirectMessage;

class GetDirectMessageAction extends DirectMessageAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $user1 = $this->checkUserAuthorization($user,(int)$this->resolveArg('user1Id'));
    if ($user1 === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    $user2Id = (int)$this->resolveArg('user2Id');
    try {
      //例外を返す可能性がある
      $user2 = $this->userRepository->findUserOfId($user2Id);
    } catch (UserNotFoundException $e) {
      $this->logger->info("user with id `$user2Id` not found.");
      return $this->respondWithData("User Not Found.", 404);
    }



    $directMessage = $this->directMessageRepository->getDirectMessages($user1,$user2);
    return $this->respondWithData($directMessage,200);
  }
}