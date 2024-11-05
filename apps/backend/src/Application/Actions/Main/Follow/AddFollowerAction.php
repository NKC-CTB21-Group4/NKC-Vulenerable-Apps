<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Main\User\User;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\Follow\FollowedNotFoundException;

class AddFollowerAction extends FollowAction
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

        // ユーザーリポジトリからユーザーを取得
        try {
            $followed = $this->userRepository->findUserOfId((int)$this->resolveArg('followedId'));
        } catch (UserNotFoundException $e) {
            $this->logger->info("User with id '{$this->resolveArg('followedId')}' not found.");
            return $this->respondWithData("User Not Found.", 404);
        }

        // 重複をチェック
        $followerId = $follower->getId();
        $followedId = $followed->getId();

        // リポジトリからuniqueCheckerメソッドを呼び出す
        if ($this->followRepository->uniqueChecker($followerId, $followedId)) {
            return $this->respondWithData(['message' => '重複']);
        }

        // フォローを追加する処理を実行
        if ($this->followRepository->addFollower($follower, $followed)) {
            return $this->respondWithData(['message' => 'Follower added successfully']);
        }

        // エラーが発生した場合の処理
        return $this->respondWithError('Failed to add follower', 500);
    }
}
