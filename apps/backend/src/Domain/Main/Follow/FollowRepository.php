<?php

declare(strict_types=1);

namespace App\Domain\Main\Follow;

use App\Domain\Main\User\User;

interface FollowerRepository
{
    /**
     * @param int $follower
     * @return array
     * @throws FollowerNotFoundException
     */
    public function findOfFollower(int $followerId): array;

    /**
     * @param int $followed
     * @return array
     * @throws FollowedNotFoundException
     */
    public function findOfFollowed(int $followedId): array;

    /**
     * @param Follower $follower
     * @param Follower $followed
     * @return bool
     * @throws FollowerCreateFailedException
     */
    public function addFollower(User $follower, User $followed): bool;

    /**
     * @param int $followerId
     * @param int $followedId
     * @return void
     * @throws FollowerNotFoundException
     * @throws FollowerDeleteFailedException
     */
    public function deleteFollwer(int $followerId, int $followedId): void;
}
