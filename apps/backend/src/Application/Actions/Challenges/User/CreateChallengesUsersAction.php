<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUser;

class CreateChallengesUsersAction extends ChallengesUserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $data = $this->getFormData();

        // 必要なデータを検証
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
          $this->logger->info("User creation failed");
          return $this->respondWithData('Invalid input',400);
        }

        $user = new ChallengesUser($data['username'],$data['email'],$data['password'],false);
        $this->userRepository->createUser($user);
        
        $this->logger->info("User created successfully");

        return $this->respondWithData($user, 201);
    }
}
