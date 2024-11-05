<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\User\ChallengesUserNotFoundException;

class UpdateChallengesDiaryAction extends ChallengesDiaryAction
{
    protected function action(): Response
    {
        $user = $this->getUserFromToken();
        $data = $this->getFormData();
        if (empty($data['diaryId'] || empty($data['title']) || empty($data['content']) || empty($data['isPublic']))) {
            $this->logger->info("Diary update failed due to invalid input");
            return $this->respondWithData('Invalid input', 400);
        }

        $userId = $user->getId();
        $diaryId = (int) $data['diaryId'];

        // ニュースが存在するか確認し、ユーザーが一致するか確認
        $diary = $this->diaryRepository->findDiaryOfId($diaryId);
        if (!$diary) {
            return $this->respondWithData("Diary not found", 404);
        }

        if ($diary->getUser()->getId() !== $user->getId()) {
            $this->logger->info("User with id `${userId}` is not authorized to update diary with id `${diaryId}`.");
            return $this->respondWithData('Unauthorized', 403);
        }

        // ニュースを削除（論理削除）
        $this->diaryRepository->update($diary,$data['title'],$data['content'],$data['isPublic']);

        $this->logger->info("Diary of id `${diaryId}` was updated by user with id `${userId}`.");

        return $this->respondWithData(['message' => 'Diary updated successfully']);
    }
}
