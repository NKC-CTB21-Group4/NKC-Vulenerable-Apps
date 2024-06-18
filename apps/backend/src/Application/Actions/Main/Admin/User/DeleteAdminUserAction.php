<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\User\UserDeleteFailedException;

class DeleteAdminUserAction extends AdminUserAction
{
  protected function action(): Response
  {
    $userId = (int) $this->resolveArg("userId");

    try {
      //例外を返す可能性がある
      $this->userRepository->deleteUser($userId);
    } catch (UserNotFoundException $e) {
      $this->logger->info("user with id `$userId` not found.");
      return $this->respondWithData("User Not Found.", 404);
    } catch (UserDeleteFailedException $e){
      $this->logger->info("Failed to delete the user.");  
    }
      
      $this->logger->info("User deleted successfully.");
      return $this->respondWithData(['message' => 'User deleted successfully']);
  }
}
