<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Admin\User;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;

class CreateAdminUserAction extends AdminUserAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $data = $this->getFormData();

    $invalidResponse = $this->validateInputData($data);
    if($invalidResponse !== null){
      return $invalidResponse;
    }

    $user = new User($data["username"],$data["email"],$data["password"],true);

    $this->userRepository->createUser($user);

    $this->logger->info("AdminUser created successfully");

    return $this->respondWithData($user, 201);    

  }

  private function validateInputData(array $data): ?Response
  {
      if (empty($data['username'] || empty($data['email'] || empty($data['password'])))) {
        $this->logger->info("AdminUser creation failed due to invalid input");
        return $this->respondWithData('Invalid input', 400);
    }
    return null;
  }
}