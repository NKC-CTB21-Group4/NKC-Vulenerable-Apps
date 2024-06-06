<?php

declare(strict_types=1);

namespace App\Domain\Main\Post;

interface PostRepository
{
    /**
     * @return Post[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Post
     * @throws PostNotFoundException
     */
    public function findUserOfId(int $id): Post;
}
