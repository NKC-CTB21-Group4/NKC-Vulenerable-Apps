<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Report;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Report\Report;
use App\Domain\Main\Report\ReportCreationFailedException;
use App\Domain\Main\Post\PostNotFoundException;
use App\Domain\Main\Tag\TagNotFoundException;

class SendReportAction extends ReportAction
{
  protected function action(): Response
  {
    $data = $this->getFormData();

    $user = $this->getUserFromToken();
    
    $user = $this->checkUserAuthorization($user);
    if ($user === null) {
      return $this->respondWithData('Unauthorized', 403);
    }
    try {
      //例外を返す可能性がある
      $post = $this->postRepository->findPostOfId($postId);
    } catch (PostNotFoundException $e) {
      $this->logger->info("post with id `$postId` not found.");
      return $this->respondWithData("Post Not Found.", 404);
    }

    $invalidResponse = $this->validateInputData($data);
    if($invalidResponse !== null){
      return $invalidResponse;
    }

    try {
      $tags = $this->tagRepository->findTagIds($data['tag_ids']);
    } catch (TagNotFoundException $e) {
      $this->logger->info("Report creation failed due to non-existent tags", ['exception' => $e]);
      return $this->respondWithData('One or more tags not found', 400);
   }

    

    $report = new Report($post,$user,$tags,$data['reason']);
    
    try {
      $this->reportRepository->create($report);
      $this->logger->info("Report of id {$report->getId()} was created successfully.");
    } catch (ReportCreationFailedException $e) {
      $this->logger->error("Report creation failed: " . $e->getMessage());
      return $this->respondWithData('Report creation failed', 500);
    }

  return $this->respondWithData($report, 201);
  }

  private function validateInputData(array $data): ?Response
  {
    if (empty($data['reason']) || empty($data['tag_ids'])) {
      $this->logger->info("Report creation failed due to invalid input");
      return $this->respondWithData('Invalid input', 400);
    }

    if (!is_array($data['tag_ids']) || !array_reduce($data['tag_ids'], fn($carry, $item) => $carry && is_int($item), true)) {
      $this->logger->info("Report creation failed due to invalid tag IDs");
      return $this->respondWithData('Invalid tag IDs', 400);
    }

    return null;
  }
}