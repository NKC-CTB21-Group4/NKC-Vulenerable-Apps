<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\Follow\FollowedNotFoundException;
use App\Domain\Main\User\UserNotFoundException;

class GetFollowersAction extends FollowAction
{
    protected function action(): Response
    {
        $userFromToken = $this->getUserFromHeader();
        $targetUserId = (int)$this->resolveArg('userId');

        if ($targetUserId === null) {
            return $this->respondWithData('User ID is required', 401);
        }

        // 対象ユーザーの情報を取得
        $targetUser = $this->userRepository->findUserOfId($targetUserId);

        if (!$targetUser) {
            return $this->respondWithData('User not found', 402);
        }

        if ($userFromToken === null) {
            // トークンがない（未ログインユーザー）の場合
            if ($targetUser->getIsPrivate()) {
                return $this->respondWithData('Cannot access private user', 403);
            }
        } else {
            // トークンがある（ログインユーザー）の場合
            $userId = $userFromToken->getId();

            if ($userId !== $targetUserId && $targetUser->getIsPrivate()) {
                $isMutualFollow = $this->followRepository->bothFollowChecker($userId, $targetUserId);

                if (!$isMutualFollow) {
                    return $this->respondWithData('Cannot access private user', 403);
                }
            }
        }

        try {
            // フォローされているユーザーを取得
            $followedUsers = $this->followRepository->findOfFollower($targetUserId);
            return $this->respondWithData($followedUsers);
        } catch (FollowerNotFoundException $e) {
            return $this->respondWithData('No followed users found', 404);
        }
    }
}
