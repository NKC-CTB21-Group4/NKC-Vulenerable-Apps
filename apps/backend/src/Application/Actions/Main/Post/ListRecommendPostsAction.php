<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;

class ListRecommendPostsAction extends PostAction
{
  protected function action(): Response
  {
      $userFromToken = $this->getUserFromHeader();
      if ($userFromToken === null) {
          // 後悔ユーザーのポストだけ表示
          try {
              // 例外を返す可能性がある
              $posts = $this->postRepository->findAllPublicPosts();
          } catch (PostNotFoundException $e) {
              $this->logger->info("Post with id `$posts` not found.");
              return $this->respondWithData("Post Not Found.", 404);
          }
      } else {
          // 公開ユーザーと鍵垢で相互フォローのユーザーのポスト表示
          $posts = $this->postRepository->findPostsForUser($userFromToken);
      }

      return $this->respondWithData($posts);
  }
}
