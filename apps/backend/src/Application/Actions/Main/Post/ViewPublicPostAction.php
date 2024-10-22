<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\Post\PostNotFoundException;

class ViewPostAction extends PostAction
{
    protected function action(): Response
    {
        // 相手側（閲覧したいユーザーと、その人のpost）のid
        $postId = (int) $this->resolveArg("postId");
        $userId = (int) $this->resolveArg("userId");

        // userIdからそのuserが鍵あかか判断
        $user = $this->userRepository->findUserOfId($userId);
        $isPrivate = $user->getIsPrivate();

        // tokenからuserId特定->FollowerId特定
        $userFromToken = $this->getUserFromToken();
        $authenticatedUser = $this->checkUserAuthorization($userFromToken);

        if ($authenticatedUser === null) {
            return $this->respondWithData('Unauthorized', 403);
        }

        $followerId = $authenticatedUser->getId();

        $bothFollowCheck = $this->followRepository->bothFollowChecker($followerId, $userId);

        if ($isPrivate && !$bothFollowCheck) {
            return $this->respondWithData("This user is Private and you are not mutual followers.", 403);
        }
        try {
            // 例外を返す可能性がある
            $post = $this->postRepository->findPublicPostOfId($followerId, $userId, $postId);
        } catch (PostNotFoundException $e) {
            $this->logger->info("Post with id `$postId` not found.");
            return $this->respondWithData("Post Not Found.", 404);
        }

        if ($post == null) {
            $this->logger->info("This userId '$userId' is Private.");
            return $this->respondWithData("This user is Private.", 405);
        }

        $this->logger->info("Post of `$postId` was viewed.");
        return $this->respondWithData($post);
    }
}
