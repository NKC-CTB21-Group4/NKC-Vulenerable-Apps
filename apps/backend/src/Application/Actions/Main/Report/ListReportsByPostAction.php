<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Report;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Report\Report;

class ListReportsByPostAction extends ReportAction
{
  protected function action(): Response
  {

    $postId = (int) $this->resolveArg("postId");

    try {
      //例外を返す可能性がある
      $post = $this->postRepository->findPostOfId($postId);
    } catch (PostNotFoundException $e) {
      $this->logger->info("post with id `$postId` not found.");
      return $this->respondWithData("Post Not Found.", 404);
    }
    
    $reports = $this->ReportRepository->findByPosts($post);

    $this->logger->info("Report List was viewed.");

    return $this->respondWithData($reports);
  }
}