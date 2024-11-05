<?php

declare(strict_types=1);

namespace App\Domain\Challenges\Diary;

interface ChallengesDiaryRepository
{
    /**
     * @param int $id
     * @return ChallengesDiary
     * @throws ChallengesDiaryNotFoundException
     */
    public function findDiaryOfId(int $id): ChallengesDiary;

    /**
     * @param ChallengesDiary $Diary
     * @return void
     */
    public function save(ChallengesDiary $Diary): void;

    /**
     * @return ChallengesDiary[]
     */
    public function findAll(): array;

    /**
     * @param ChallengesDiary $Diary
     * @return void
     */
    public function delete(ChallengesDiary $Diary): void;

    /**
     * @param ChallengesDiary $Diary
     * @return ChallengesDiary
     */
    public function create(ChallengesDiary $Diary): ChallengesDiary;

    /**
     * @param int $id
     * @param ChallengesDiary $Diary
     * @return ChallengesDiary
     * @throws ChallengesDiaryNotFoundException
     */
    public function update(ChallengesDiary $existingDiary,$title,$content,$isPublic ): ChallengesDiary;
}
