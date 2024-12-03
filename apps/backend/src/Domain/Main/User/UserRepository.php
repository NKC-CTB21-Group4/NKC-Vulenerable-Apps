<?php

declare(strict_types=1);

namespace App\Domain\Main\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;

    /**
     * @return array
     */
    public function findAllUserIds() : array;

    /**
     * @param User $user
     * @return User
     */
    public function createUser(User $user): User;

    /**
     *  @param User $user 
     *  @return void
     *  @throws UserNotFoundException
     *  @throws UserDeleteFailedException
     */
    public function deleteUser(int $id) : void;

    /**
     * @param string $email
     * @param string $password
     * @return User
     */
    public function findByEmailAndPassword(string $email, string $password): User;

    /**
     * @param int $id
     * @param array $userInfo
     * @return User
     */
    public function updateUser(int $id,array $userInfo):User;

    /**
     * @param array $searchParam
     * - 'keyword': string, optional - The keyword to search in users
     * - 'userId': int, optional - The user ID to filter users
     * - 'onlyFromFollowedUser': - boolean , optional - If true, only followed users will be included
     * @return array $users
     * @throws UserNotFoundException
     * @throws UserSearchFailedException
     */

    public function search(array $searchParam): array;

    public function toggleIsPrivate(User $user):User;

}
