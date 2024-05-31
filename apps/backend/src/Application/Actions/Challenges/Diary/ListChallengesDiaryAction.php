<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;

class ListChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $diaryList = $this->diaryRepository->findAll();

        $this->logger->info("Diary list was viewed.");

        return $this->respondWithData($diaryList);
    }
}
