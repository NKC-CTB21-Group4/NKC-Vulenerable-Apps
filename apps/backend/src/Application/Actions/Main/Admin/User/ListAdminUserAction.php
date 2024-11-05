<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;

class ListAdminUserAction extends AdminUserAction
{
  protected function action(): Response
  {
    $users = $this->userRepository->findAll();

    $this->logger->info("User List was viewed.");

    return $this->respondWithData($users);  
  }
}