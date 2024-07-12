<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Follow\FollowRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\User;
use Psr\Http\Message\ResponseInterface as Response;

abstract class FollowAction extends Action
{
    protected FollowRepository $FollowRepository;
    protected UserRepository $userRepository;

    public function __construct(
        LoggerInterface $logger,
        FollowRepository $followRepository,
        UserRepository $userRepository
    ){
        parent::__construct($logger);
        $this->followRepository = $followRepository;
        $this->userRepository = $userRepository;
    }

    protected function getUserFromToken():?object
    {
        return (object)$this->request->getAttribute('token')['user'] ?? null;
    }

    protected function checkUserAuthorization(?object $user): ?User
    {
      if($user === null)return null;
        $userId = (int) $this->resolveArg('userId');
        $user = $this->userRepository->findUserOfId($user->id);
        if ($user->getId() !== $userId) {
            return null;
        }
        return $user;
    }
}



