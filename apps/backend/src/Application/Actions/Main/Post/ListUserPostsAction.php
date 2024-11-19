<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use Psr\Http\Message\ResponseInterface as Response;

use App\Domain\Main\Post\Post;
use App\Domain\Main\Post\PostNotFoundException;
use App\Domain\Main\User\UserNotFoundException;


class ListUserPostsAction extends PostAction
{
  protected function action():Response
  {
    $userFromToken = $this->getUserFromHeader();
    //自分
    $userId = $userFromToken ? $userFromToken->getId() : null;
    //相手
    $followerId = (int)$this->resolveArg('userId');
    $follower = $this->userRepository->findUserOfId($followerId);
    $isPrivate = $follower->getIsPrivate();
    
    if ($userId === null) {
      //操作ユーザーがログインをしていなかったら
      //Privateか判定
      if($isPrivate === false) {
        try {
          //公開ユーザーの場合だけ表示
          $posts = $this->postRepository->findPostOfUser($follower);
        } catch (PostNotFoundException $e) {
            $this->logger->info("Post with id `$posts` not found.");
            return $this->respondWithData("Post Not Found.", 404);
        }
      } else {
        return $this->respondWithData("This follower is private.", 404);
      }
    } else {
        //操作ユーザーがログインをしていたら
      $bothFollowCheck = $this->followRepository->bothFollowChecker($followerId, $userId);
      if ($isPrivate && !$bothFollowCheck) {
          return $this->respondWithData("This user is Private and you are not mutual followers.", 403);
      }
      $posts = $this->postRepository->findPostOfUser($follower);
    }

  return $this->respondWithData($posts);

  //   // userIdからそのuserが鍵あかか判断
  //   $user = $this->userRepository->findUserOfId($userId);
  //   $isPrivate = $user->getIsPrivate();

  //   $bothFollowCheck = $this->followRepository->bothFollowChecker($followerId, $userId);
  //   if ($isPrivate && !$bothFollowCheck) {
  //       return $this->respondWithData("This user is Private and you are not mutual followers.", 403);
  //   }

  //   try {
  //     // 例外を返す可能性がある
  //     $posts = $this->postRepository->findPostOfUser($user);
  //   } catch (PostNotFoundException $e) {
  //       $this->logger->info("Post with id `$postId` not found.");
  //       return $this->respondWithData("Post Not Found.", 404);
  // }

  // if ($posts == null) {
  //     $this->logger->info("This userId '$userId' is Private.");
  //     return $this->respondWithData("This user is Private.", 405);
  // }
    
  //   $username = $user->getUsername();

  //   $this->logger->info("${username}User's Post list was viewed.");

  //   return $this->respondWithData($posts);
  // }
  }
}