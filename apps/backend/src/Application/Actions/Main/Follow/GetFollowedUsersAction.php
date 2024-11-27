<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\UserNotFoundException;

class GetFollowedUsersAction extends FollowAction
{
    protected function action(): Response
    {
        // リクエストから対象ユーザーIDを取得
        $targetUserId = (int)$this->resolveArg('userId');

        if ($targetUserId === null) {
            return $this->respondWithData('User ID is required', 400);
        }

        // 対象ユーザーの情報を取得
        $targetUser = $this->userRepository->findUserOfId($targetUserId);

        if (!$targetUser) {
            return $this->respondWithData('User not found', 404);
        }

        // 鍵アカウントかどうかをチェック
        if ($targetUser->getIsPrivate()) {
            return $this->respondWithData('Cannot access private user', 403);
        }

        try {
            // フォローされているユーザーを取得
            $followedUsers = $this->followRepository->findOfFollowed($targetUserId);
            return $this->respondWithData($followedUsers);
        } catch (FollowedNotFoundException $e) {
            return $this->respondWithData('Failed to get followed users: ' . $e->getMessage(), 404);
        }
    }
}
