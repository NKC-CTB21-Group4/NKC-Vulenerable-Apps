<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Challenges\Diary;

use App\Domain\Challenges\Diary\ChallengesDiary;
use App\Domain\Challenges\Diary\ChallengesDiaryRepository;
use App\Domain\Challenges\Diary\ChallengesDiaryNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseChallengesDiaryRepository extends EntityRepository implements ChallengesDiaryRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(ChallengesDiary::class));
    }

    /**
     * {@inheritdoc}
     */

    private function isDeleted($diary)
    {
        return $diary->getDeletedAt() !== null;  
    }

    public function findDiaryOfId(int $id): ChallengesDiary
    {
        /** @var ChallengesDiary $diary */
        $diary = parent::find($id);

        if ($diary === null || $this->isDeleted($diary)) {
          throw new ChallengesDiaryNotFoundException();
        }

        return $diary;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ChallengesDiary $diary): void
    {
        $this->_em->persist($diary);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
      return array_filter(parent::findAll(),function($diary) {
        return !$this->isDeleted($diary);
    });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ChallengesDiary $diary): void
    {
        $diary->setDeletedAt(new \DateTime('now'));
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function create(ChallengesDiary $diary): ChallengesDiary
    {
        $this->_em->persist($diary);
        $this->_em->flush();
        return $diary;
    }

    /**
     * {@inheritdoc}
     */
    public function update(ChallengesDiary $existingDiary, $title,$content,$isPublic): ChallengesDiary
    {
        // 必要なプロパティを更新
        $existingDiary->setTitle($title);
        $existingDiary->setContent($content);
        $existingDiary->setIsPublic($isPublic);

        $this->_em->flush();

        return $existingDiary;
    }
}
