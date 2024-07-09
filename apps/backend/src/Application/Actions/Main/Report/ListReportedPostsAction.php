<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Report;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Report\Report;

class ListReportedPostsAction extends ReportAction
{
  protected function action(): Response
  {
    
    $reports = $this->ReportRepository->getReportedPosts();

    $this->logger->info("Reported Post List was viewed.");

    return $this->respondWithData($reports);
  }
}