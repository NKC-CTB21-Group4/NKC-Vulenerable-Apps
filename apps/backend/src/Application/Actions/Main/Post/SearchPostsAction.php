<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;

class SearchPostsAction extends PostAction
{
  protected function action(): Response
  {
    
     // リクエストから検索条件を取得
     $queryParams = $this->request->getQueryParams();
        
     // 検索条件を設定
     $searchCriteria = [];
     
     if (!empty($queryParams['authorId'])) {
      $searchCriteria['authorId'] = (int)$queryParams['authorId'];
     }

     if (!empty($queryParams['authorName'])){
      $searchCriteria['authorName'] = $queryParams['authorName'];
     }

     if (!empty($queryParams['keyword'])) {
         $searchCriteria['keyword'] = $queryParams['keyword'];
     }

     if (!empty($queryParams['dateFrom'])) {
         $searchCriteria['dateFrom'] = $queryParams['dateFrom'];
     }

     if (!empty($queryParams['dateTo'])) {
         $searchCriteria['dateTo'] = $queryParams['dateTo'];
     }

     // 検索実行
     $posts = $this->postRepository->search($searchCriteria);

     $this->logger->info("Post search was executed.");

     // 検索結果を返す
     return $this->respondWithData($posts);
  }
}