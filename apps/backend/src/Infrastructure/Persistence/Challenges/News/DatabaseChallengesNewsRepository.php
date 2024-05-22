<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Challenges\News;

use App\Domain\Challenges\News\ChallengesNews;
use App\Domain\Challenges\News\ChallengesNewsRepository;
use App\Domain\Challenges\News\ChallengesNewsNotFoundException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseChallengesNewsRepository extends EntityRepository implements ChallengesNewsRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(ChallengesNews::class));
    }

    /**
     * {@inheritdoc}
     */

    private function isDeleted($news)
    {
        return $news->getDeletedAt() !== null;  
    }

    public function findNewsOfId(int $id): ChallengesNews
    {
        /** @var ChallengesNews $news */
        $news = parent::find($id);

        if ($news === null || $this->isDeleted($news)) {
          throw new ChallengesNewsNotFoundException();
        }

        return $news;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ChallengesNews $news): void
    {
        $this->_em->persist($news);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
      return array_filter(parent::findAll(),function($news) {
        return !$this->isDeleted($news);
    });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ChallengesNews $news): void
    {
        $news->setDeletedAt(new \DateTime('now'));
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function create(ChallengesNews $news): ChallengesNews
    {
        $this->_em->persist($news);
        $this->_em->flush();
        return $news;
    }

    /**
     * {@inheritdoc}
     */
    public function update(ChallengesNews $existingNews, $title,$content,$isPublic): ChallengesNews
    {
        // 必要なプロパティを更新
        $existingNews->setTitle($title);
        $existingNews->setContent($content);
        $existingNews->setIsPublic($isPublic);

        $this->_em->flush();

        return $existingNews;
    }
}
