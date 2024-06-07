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
     * @param void
     * @return bool
     */
    public function togglePostFav():bool;

}
