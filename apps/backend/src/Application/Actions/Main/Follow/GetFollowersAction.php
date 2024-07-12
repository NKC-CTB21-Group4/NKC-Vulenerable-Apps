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
        // ユーザーのトークンからユーザー情報を取得
        $user = $this->getUserFromToken();
        
        $authorizedUser = $this->checkUserAuthorization($user);
        if ($authorizedUser === null) {
            return $this->respondWithData('Unauthorized', 403);
        }

        try {
            $followers = $this->followRepository->findOfFollower($authorizedUser->getId());
            
            return $this->respondWithData($followers);
        } catch (FollowerNotFoundException $e) {
            return $this->respondWithError('Failed to get followers: ' . $e->getMessage(), 500);
        }
    }
}
