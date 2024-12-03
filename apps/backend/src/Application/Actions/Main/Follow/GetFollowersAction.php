<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Main\Follow\Follow;
use App\Domain\Main\Follow\FollowerNotFoundException;

class GetFollowersAction extends FollowAction
{
    protected function action(): Response
    {

        $userFromToken = $this->getUserFromHeader();
        //ログインユーザーのidを取得
        $userId = $userFromToken ? $userFromToken->getId() : null;

        // リクエストから対象ユーザーIDを取得
        $targetUserId = (int)$this->resolveArg('userId');

        if ($targetUserId === null) {
            return $this->respondWithData('User ID is required', 400);
        }

        //ログインユーザーとターゲットユーザーが同じだった場合はそのまま情報を出す
        if ($userId !== $targetUserId){
            // 対象ユーザーの情報を取得
            $targetUser = $this->userRepository->findUserOfId($targetUserId);

            if (!$targetUser) {
                return $this->respondWithData('User not found', 404);
            }

            // 鍵アカウントかどうかをチェック
            if ($targetUser->getIsPrivate()) {
                $isMutualFollow = $this->followRepository->bothFollowChecker($userId, $targetUserId);

                if (!$isMutualFollow) {
                    return $this->respondWithData('Cannot access private user', 403);
                }
            }
        }

        try {//任意のユーザー出ないといけない
            $followers = $this->followRepository->findOfFollower($targetUserId);
            
            return $this->respondWithData($followers);
        } catch (FollowerNotFoundException $e) {
            return $this->respondWithError('Failed to get followers: ' . $e->getMessage(), 500);
        }
    }
}
