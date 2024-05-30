<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use Psr\Http\Message\ResponseInterface as Response;

class ViewMyChallengesDiaryAction extends ChallengesDiaryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = $this->request->getAttribute('token')['user']['id'];
        $id = (int) $this->resolveArg('id');
        $diary = $this->diaryRepository->findDiaryOfId($id);
        if($diary->getUser()->getId() !== $userId){
          return $this->respondWithData("diary of different users");
        }
        
        $this->logger->info("diary of id `${id}` was viewed.");
        return $this->respondWithData($diary);
    }
}
