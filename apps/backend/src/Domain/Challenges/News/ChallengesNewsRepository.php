<?php

declare(strict_types=1);

namespace App\Domain\Challenges\News;

interface ChallengesNewsRepository
{
    /**
     * @param int $id
     * @return ChallengesNews
     * @throws ChallengesNewsNotFoundException
     */
    public function findNewsOfId(int $id): ChallengesNews;

    /**
     * @param ChallengesNews $news
     * @return void
     */
    public function save(ChallengesNews $news): void;

    /**
     * @return ChallengesNews[]
     */
    public function findAll(): array;

    /**
     * @param ChallengesNews $news
     * @return void
     */
    public function delete(ChallengesNews $news): void;

    /**
     * @param ChallengesNews $news
     * @return ChallengesNews
     */
    public function create(ChallengesNews $news): ChallengesNews;

    /**
     * @param int $id
     * @param ChallengesNews $news
     * @return ChallengesNews
     * @throws ChallengesNewsNotFoundException
     */
    public function update(ChallengesNews $existingNews,$title,$content,$isPublic ): ChallengesNews;
}
