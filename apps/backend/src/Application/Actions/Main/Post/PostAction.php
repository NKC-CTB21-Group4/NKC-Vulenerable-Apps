<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\User;
use App\Domain\Main\Follow\FollowRepository;
use Psr\Http\Message\ResponseInterface as Response;

abstract class PostAction extends Action
{
    protected PostRepository $postRepository;
    protected UserRepository $userRepository;
    protected FollowRepository $followRepository;

    public function __construct(
        LoggerInterface $logger,
        PostRepository $postRepository,
        UserRepository $userRepository,
        FollowRepository $followRepository
    ){
        parent::__construct($logger);
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->followRepository = $followRepository;
    }

    protected function getUserFromToken(): ?object
    {
        return (object)$this->request->getAttribute('token')['user'] ?? null;
    }

    protected function checkUserAuthorization(?object $user): ?User
    {
        if ($user === null) return null;
        $userId = (int) $user->id;
        $authenticatedUser = $this->userRepository->findUserOfId($userId);
        return $authenticatedUser;
    }
}
