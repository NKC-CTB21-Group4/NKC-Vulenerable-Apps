<?php 

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUser;
use App\Domain\Challenges\Auth\ChallengesAuthToken;

class ChallengesGenerateTokenAction extends ChallengesAuthentication
{
  protected function action(): Response
  {
    $data = $this->getFormData();

    if (empty($data['email']) || empty($data['password'])) {
      $this->logger->info("login failed due to invalid input");
      return $this->respondWithData('Invalid input', 400);
    }

    $token = $this->generateToken($data['email'],$data['password']);
    if($token === null){
      $this->logger->info("Failed to issue token");
      return $this->respondWithData("certification failed");
    }

    $this->logger->info("The token was successfully issued");
    return $this->respondWithData($token, 200);
  }


  private function generateToken(string $email, string $password): ?string {
    $user = $this->userRepository->findByEmailAndPassword($email, $password);

    if ($user !== null) {
        $payload = ['user' => $user];
        $token = $this->jwtService->generateToken($payload);
        return $token;
    }

    return null; 
  }
}