<?php

declare(strict_types=1);

namespace App\Domain\Main\Reaction;

interface ReactionRepository
{
   /**
     * @param int $postId
     * @return int
     */
    public function getFavsCountByPostId(int $id): int;

    /**
     * @param int $userId
     * @param int $postId
     * @return bool
     */
    public function togglePostFav(int $userId,int $postId):bool;

}
