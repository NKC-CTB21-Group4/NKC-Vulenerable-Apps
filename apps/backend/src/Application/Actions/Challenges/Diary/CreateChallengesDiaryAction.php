<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\Diary\ChallengesDiary;


class CreateChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        // 必要なデータを検証
        if (empty($data['userId']) || empty($data['title']) || empty($data['content']) || empty($data['isPublic'])) {
            $this->logger->info("Diary creation failed due to invalid input");
            return $this->respondWithData('Invalid input', 400);
        }
        $userId = (int) $data['userId'];

        $diary = new ChallengesDiary(
            $this->userRepository->findUserOfId($userId),
            $data['title'],
            $data['content'],
            $data['isPublic']
        );

        $this->diaryRepository->create($diary);

        $this->logger->info("Diary created successfully");

        return $this->respondWithData($diary, 201);
    }
}
