<?php

declare(strict_types=1);

namespace App\Domain\Challenges\User;

interface ChallengesUserRepository
{
    /**
     * @return ChallengesUser[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return ChallengesUser
     * @throws ChallengesUserNotFoundException
     */
    public function findUserOfId(int $id): ChallengesUser;

    /**
     * @param int $id
     * @throws ChallengesUserNotFoundException
     */
    public function deleteUser(int $id): void;

    /**
     * @param ChallengesUser $user
     * @return ChallengesUser
     */
    public function createUser(ChallengesUser $user): ChallengesUser;

    // /**
    //  * @param int $id
    //  * @param ChallengesUser $user
    //  * @return ChallengesUser
    //  * @throws ChallengesUserNotFoundException
    //  */
    // public function updateUser(int $id, ChallengesUser $user): ChallengesUser;

    /**
     * @param string $email
     * @param string $password
     * @return ChallengesUser
     * @throws ChallengesUserAuthenticationFailureException
     */
    public function findByEmailAndPassword(string $email, string $password): ChallengesUser;
}
