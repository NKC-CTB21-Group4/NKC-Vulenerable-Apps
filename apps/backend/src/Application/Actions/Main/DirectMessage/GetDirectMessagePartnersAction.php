<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\DirectMessage;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\DirectMessage\DirectMessageCreationException;
use App\Domain\Main\DirectMessage\DirectMessage;

class GetDirectMessagePartnersAction extends DirectMessageAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $user= $this->checkUserAuthorization($user,(int)$this->resolveArg('userId'));
    if ($user === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    $partners = $this->directMessageRepository->getDirectMessagePartners($user);
    return $this->respondWithData($partners,200);
  }
}