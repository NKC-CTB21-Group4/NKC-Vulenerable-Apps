<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;

class ViewChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $diary = $this->diaryRepository->findDiaryOfId($id);

        $this->logger->info("Diary of id `${id}` was viewed.");

        return $this->respondWithData($diary);
    }
}
