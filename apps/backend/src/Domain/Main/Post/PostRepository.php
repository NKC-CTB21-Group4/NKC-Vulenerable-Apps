<?php

declare(strict_types=1);

namespace App\Domain\Main\Post;

use App\Domain\Main\User\User;


interface PostRepository
{
    /**
     * @return Post[]
     */
    public function findAll(): array;

    /**
     * @param 
     * @return Post[]
     */
    public function findAllPublicPosts(): array;

    /**
     * @return Post[]
     */
    public function getRecommendedPosts():array;


    /**
     * @param User $user
     * @return Post[]
     * @throws PostNotFoundException
     */
    public function findPostOfUser(User $user): array;
    

    /**
     * @param int $id
     * @return Post
     * @throws PostNotFoundException
     */
    public function findPostOfId(int $id): Post;

    /**
     * @param int $postId
     * @param int $userId
     * @return Post|null
     * @throws PostNotFoundException
     */
    public function findPublicPostOfId(int $myUserId, int $otherUserId, bool $otherUserPostId): ?Post;

    /**
     * @param Post $post
     * @return Post
     * @throws PostCreateFailedException
     */
    public function create(Post $post): Post;

    /**
     * @param int $postId
     * @return void
     * @throws PostNotFoundException
     * @throws PostDeleteFailedException
     */
    public function delete(int $postId):void;
}
