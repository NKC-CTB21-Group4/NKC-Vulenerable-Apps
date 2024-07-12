<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Main\User\User;
use App\Domain\Main\Follow\Follow;
use App\Domain\Main\Follow\FollowerNotFoundException;

class DeleteFollowerAction extends FollowAction
{
    protected function action(): Response
    {
        // ユーザーのトークンからユーザー情報を取得
        $user = $this->getUserFromToken();

        // ユーザーの認可を確認
        $follower = $this->checkUserAuthorization($user);
        if ($follower === null) {
            return $this->respondWithData('Unauthorized', 403);
        }

        try {
            $followed = $this->userRepository->findUserOfId((int)$this->resolveArg('followedId'));
        } catch (UserNotFoundException $e) {
            $this->logger->info("User with id '{$this->resolveArg('followedId')}' not found.");
            return $this->respondWithData("User Not Found.", 404);
        }

        try {
            $this->followRepository->deleteFollower((int)$follower->getId(), (int)$followed->getId());
            return $this->respondWithData(['message' => 'Follower deleted successfully']);
        } catch (FollowerDeleteFailedException $e) {
            return $this->respondWithError('Failed to delete follower: ' . $e->getMessage(), 500);
        }
    }
}
