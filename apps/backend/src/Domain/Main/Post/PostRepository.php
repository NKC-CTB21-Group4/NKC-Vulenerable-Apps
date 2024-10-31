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
    public function findPublicPostOfId(int $followerId, int $userId, int $postId): ?Post;

    /**
     * @param User $user
     * @return Post[]
     */
    public function findPostsForUser(User $user) : array;

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

    /**
     * @param array $searchCriteria
     * - 'keyword': string, optional - The keyword to search in posts
     * - 'authorId': int, optional - The user ID to filter posts by author
     * - 'authorName': string optional - The username to filiter posts by author
     * - 'dateFrom': string (Y-m-d), optional - The start date for filtering posts
     * - 'dateTo': string (Y-m-d), optional - The end date for filtering posts
     * - 'sortBy': string, optional - The field to sort the results (e.g., 'created_at')
     * - 'onlyFromFollowedUser': - boolean , optional - If true, only posts from followed users will be included
     * @return array - The search results as an array of posts
     * @throws PostNotFoundException
     */
    public function search(array $searchCriteria): array;

}
