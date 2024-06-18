<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;

class ViewAdminUserAction extends AdminUserAction
{
  protected function action(): Response
  {
    $userId = (int) $this->resolveArg("userId");

    try {
      //例外を返す可能性がある
      $user = $this->userRepository->findUserOfId($userId);
  } catch (UserNotFoundException $e) {
      $this->logger->info("user with id `$userId` not found.");
      return $this->respondWithData("User Not Found.", 404);
  }
    $this->logger->info("user of `$userId` was viewed.");
    return $this->respondWithData($user);
  }
}
