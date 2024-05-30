<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;

class ViewPublicChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $diary = $this->diaryRepository->findDiaryOfId($id);
        if(!$diary->isPublic()){
          return $this->respondWithData("is not Public");
        }
        
        $this->logger->info("public diary of id `${id}` was viewed.");
        return $this->respondWithData($diary);
    }
}
