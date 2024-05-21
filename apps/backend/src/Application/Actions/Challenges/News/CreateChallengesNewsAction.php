<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\News;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\News\ChallengesNews;


class CreateChallengesNewsAction extends ChallengesNewsAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        // 必要なデータを検証
        if (empty($data['userId']) || empty($data['title']) || empty($data['content']) || empty($data['isPublic'])) {
            $this->logger->info("News creation failed due to invalid input");
            return $this->respondWithData('Invalid input', 400);
        }
        $userId = (int) $data['userId'];

        $news = new ChallengesNews(
            $this->userRepository->findUserOfId($userId),
            $data['title'],
            $data['content'],
            $data['isPublic']
        );

        $this->newsRepository->create($news);

        $this->logger->info("News created successfully");

        return $this->respondWithData($news, 201);
    }
}
