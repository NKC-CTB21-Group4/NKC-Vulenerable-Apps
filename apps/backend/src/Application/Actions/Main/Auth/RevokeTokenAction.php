<?php 

declare(strict_types=1);

namespace App\Application\Actions\Main\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\User;
use App\Domain\Main\Auth\AuthToken;

class RevokeTokenAction extends Authentication
{
  protected function action(): Response
  {
    $authHeader = $this->request->getHeader('Authorization');

    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => 'Token not provided']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    $token = $matches[1];

    if (empty($token)) {
      $this->logger->info("Token not found in headers");
      return $this->respondWithData('Token not found in headers', 400);
    }

    $result = $this->jwtService->revokeToken($token);
    if ($result){
      return $this->respondWithData('Token Revoked',200);
    }
    return $this->respondWithData('Failed to invalidate token',400);
  }
}
