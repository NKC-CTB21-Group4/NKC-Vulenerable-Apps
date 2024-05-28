<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\News;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\User\ChallengesUserNotFoundException;

class DeleteChallengesNewsAction extends ChallengesNewsAction
{
    protected function action(): Response
    {
        $data = $this->getFormData();

        if (empty($data['userId']) || empty($data['newsId'])) {
            $this->logger->info("News deletion failed due to invalid input");
            return $this->respondWithData('Invalid input', 400);
        }

        $userId = (int) $data['userId'];
        $newsId = (int) $data['newsId'];

        // ユーザーが存在するか確認
        try {
            $user = $this->userRepository->findUserOfId($userId);
        } catch (ChallengesUserNotFoundException $e) {
            $this->logger->info("User with id `${userId}` not found.");
            return $this->respondWithData('User not found', 404);
        }

        // ニュースが存在するか確認し、ユーザーが一致するか確認
        $news = $this->newsRepository->findNewsOfId($newsId);
        if ($news->getUser()->getId() !== $user->getId()) {
            $this->logger->info("User with id `${userId}` is not authorized to delete news with id `${newsId}`.");
            return $this->respondWithData('Unauthorized', 403);
        }

        // ニュースを削除（論理削除）
        $this->newsRepository->delete($news);

        $this->logger->info("News of id `${newsId}` was deleted by user with id `${userId}`.");

        return $this->respondWithData(['message' => 'News deleted successfully']);
    }
}
