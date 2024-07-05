<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\DirectMessage;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\DirectMessage\DirectMessageRepository;
use App\Domain\Main\User\User;
use App\Domain\Main\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;

abstract class DirectMessageAction extends Action
{
  protected DirectMessageRepository $directMessageRepository;
  protected UserRepository $userRepository;

  public function __construct(
    LoggerInterface $logger,
    DirectMessageRepository $directMessageRepository,
    UserRepository $userRepository
  ){
    parent::__construct($logger);
    $this->directMessageRepository = $directMessageRepository;
    $this->userRepository = $userRepository;
  }

  protected function getUserFromToken():?object
  {
      return (object)$this->request->getAttribute('token')['user'] ?? null;
  }

  protected function checkUserAuthorization(?object $user,$userId): ?User
    {
      if($user === null)return null;
        $user = $this->userRepository->findUserOfId($user->id);
        if ($user->getId() !== $userId) {
            return null;
        }
        return $user;
    }

}
