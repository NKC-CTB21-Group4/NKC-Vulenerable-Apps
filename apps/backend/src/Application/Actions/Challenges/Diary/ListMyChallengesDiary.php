<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;

class ListMyChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $user = $this->getUserFromToken();
        if (!$user) {
            return $this->respondWithData('Unauthorized', 401);
        }
        
        $diaryList = $this->diaryRepository->findByUserId($userId);

        $this->logger->info("my diary list was viewed.");

        return $this->respondWithData($diaryList);
    }
}
