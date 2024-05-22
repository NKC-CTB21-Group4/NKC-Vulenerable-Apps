<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\News;

use Psr\Http\Message\ResponseInterface as Response;

class ViewChallengesNewsAction extends ChallengesNewsAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $id = (int) $this->resolveArg('id');
        $news = $this->newsRepository->findNewsOfId($id);

        $this->logger->info("News of id `${id}` was viewed.");

        return $this->respondWithData($news);
    }
}
