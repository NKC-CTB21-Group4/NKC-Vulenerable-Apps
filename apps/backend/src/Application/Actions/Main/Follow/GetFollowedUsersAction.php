<?php

declare(strict_types=1);

namespace App\Application\Action\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Main\Follow\Follow;
use App\Domain\Main\Follow\FollowedNotFoundException;

class GetFollowedUsersAction extends FollowAction
{
    protected function action(Request $request): Response
    {
        $followedId = (int)$request->getAttribute('followed_id');

        try {
            $followedUsers = $this->followRepository->findOfFollowed($followedId);
            return $this->respondWithData($followedUsers);
        } catch (FollowedNotFoundException $e) {
            return $this->respondWithError('Failed to get followed users: ' . $e->getMessage(), 500);
        }
    }
}
