<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Report;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Report\Report;
use App\Domain\Main\Report\ReportNotFoundException;

class ViewReportAction extends ReportAction
{
  protected function action(): Response
  {
    $reportId = (int) $this->resolveArg("reportId");

    try {
      //例外を返す可能性がある
      $report = $this->reportRepository->findReportOfId($reportId);
    } catch (ReportNotFoundException $e) {
        $this->logger->info("report with id `$reportId` not found.");
        return $this->respondWithData("Report Not Found.", 404);
    }
      $this->logger->info("report of `$reportId` was viewed.");
      return $this->respondWithData($report);
    }
}