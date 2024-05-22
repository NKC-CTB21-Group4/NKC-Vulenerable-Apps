<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\User\ChallengesUserNotFoundException;

class DeleteChallengesDiaryAction extends ChallengesDiaryAction
{
    protected function action(): Response
    {
        $data = $this->getFormData();

        if (empty($data['userId']) || empty($data['diaryId'])) {
            $this->logger->info("Diary deletion failed due to invalid input");
            return $this->respondWithData('Invalid input', 400);
        }

        $userId = (int) $data['userId'];
        $diaryId = (int) $data['diaryId'];

        // ユーザーが存在するか確認
        try {
            $user = $this->userRepository->findUserOfId($userId);
        } catch (ChallengesUserNotFoundException $e) {
            $this->logger->info("User with id `${userId}` not found.");
            return $this->respondWithData('User not found', 404);
        }

        // ニュースが存在するか確認し、ユーザーが一致するか確認
        $diary = $this->diaryRepository->findDiaryOfId($diaryId);
        if ($diary->getUser()->getId() !== $user->getId()) {
            $this->logger->info("User with id `${userId}` is not authorized to delete diary with id `${diaryId}`.");
            return $this->respondWithData('Unauthorized', 403);
        }

        // ニュースを削除（論理削除）
        $this->diaryRepository->delete($diary);

        $this->logger->info("Diary of id `${diaryId}` was deleted by user with id `${userId}`.");

        return $this->respondWithData(['message' => 'Diary deleted successfully']);
    }
}
