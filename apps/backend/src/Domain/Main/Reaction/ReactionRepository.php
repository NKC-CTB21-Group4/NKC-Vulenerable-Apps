<?php

declare(strict_types=1);

namespace App\Domain\Main\Reaction;

use App\Domain\Main\User\User;
use App\Domain\Main\Post\Post;

interface ReactionRepository
{
   /**
     * @param int $postId
     * @return int
     */
    public function getFavsCountByPostId(int $id): int;

    /**
     * @param User $user
     * @param Post $post
     * @return bool
     */
    public function togglePostFav(User $user,Post $post):bool;

}
