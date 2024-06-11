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
     * @return Post[]
     */
    public function getRecommendedPosts():array;
    

    /**
     * @param int $id
     * @return Post
     * @throws PostNotFoundException
     */
    public function findPostOfId(int $id): Post;

    /**
     * @param Post $post
     * @return Post
     * @throws PostCreateFailedException
     */
    public function createPost(Post $post): Post;

    /**
     * @param int $postId
     * @return void
     * @throws PostNotFoundException
     * @throws PostDeleteFailedException
     */
    public function deletePost(int $postId):void;
}
