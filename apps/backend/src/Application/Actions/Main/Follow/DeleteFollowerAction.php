<?php

declare(strict_types=1);

namespace App\Application\Action\Main\Follow;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Main\Follow\Follow;
use App\Domain\Main\Follow\FollowerNotFoundException;

class DeleteFollowerAction extends FollowAction
{
    protected function action(Request $request): Response
    {
        $data = $request->getParsedBody();

        $followerId = (int)$data['follower_id'];
        $followedId = (int)$data['followed_id'];

        try {
            $this->followRepository->deleteFollower($followerId, $followedId);
            return $this->respondWithData(['message' => 'Follower deleted successfully']);
        } catch (FollowerDeleteFailedException $e) {
            return $this->respondWithError('Failed to delete follower: ' . $e->getMessage(), 500);
        }
    }
}
