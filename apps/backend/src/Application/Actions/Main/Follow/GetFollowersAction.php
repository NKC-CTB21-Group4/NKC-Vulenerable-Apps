<?php

declare(strict_types=1);

namespace App\Application\Action\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Main\Follow\Follow;
use App\Domain\Main\Follow\FollowerNotFoundException;

class GetFollowersAction extends FollowAction
{
    protected function action(Request $request): Response
    {
        $followerId = (int)$request->getAttribute('follower_id');

        try {
            $followers = $this->followRepository->findOfFollower($followerId);
            return $this->respondWithData($followers);
        } catch (FollowerNotFoundException $e) {
            return $this->respondWithError('Failed to get followers: ' . $e->getMessage(), 500);
        }
    }
}
