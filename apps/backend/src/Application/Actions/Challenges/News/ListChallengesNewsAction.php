<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\News;

use Psr\Http\Message\ResponseInterface as Response;

class ListChallengesNewsAction extends ChallengesNewsAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $newsList = $this->newsRepository->findAll();

        $this->logger->info("News list was viewed.");

        return $this->respondWithData($newsList);
    }
}
