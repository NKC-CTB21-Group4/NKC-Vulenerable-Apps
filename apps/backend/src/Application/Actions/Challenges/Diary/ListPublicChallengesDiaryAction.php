<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;

class ListPublicChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $diaryList = $this->diaryRepository->findAll();

        $publicDiaryList = $this->diaryRepository->findAllPublicDiaries();

        $this->logger->info("public diary list was viewed.");

        return $this->respondWithData($publicDiaryList);
    }
}
