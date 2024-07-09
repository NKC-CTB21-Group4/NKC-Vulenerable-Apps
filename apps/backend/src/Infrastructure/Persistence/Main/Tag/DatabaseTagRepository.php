<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Tag;

use App\Domain\Main\Tag\Tag;
use App\Domain\Main\Tag\TagRepository;
use App\Domain\Main\Tag\TagNotFoundException;
use App\Domain\Main\Tag\TagCreationFailedException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseTagRepository extends EntityRepository implements TagRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(Tag::class));
    }

    public function save(Tag $tag): void
    {
        $this->_em->persist($tag);
        $this->_em->flush();
    }

    /**
     * @param Tag $tag
     * @throws TagCreationFailedException
     */
    public function create(Tag $tag): void
    {
        try {
            $this->_em->persist($tag);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new TagCreationFailedException('Failed to create tag.', 0, $e);
        }
    }

    /**
     * @return Tag[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $tagId
     * @throws TagNotFoundException
     * @return void
     */
    public function remove(int $tagId): void
    {
        $tag = parent::find($tagId);

        if ($tag === null) {
            throw new TagNotFoundException();
        }

        $this->_em->remove($tag);
        $this->_em->flush();
    }
    public function findTagIds(array $tagIds): array
    {
        $tags = $this->createQueryBuilder('t')
            ->andWhere('t.id IN (:tagIds)')
            ->setParameter('tagIds', $tagIds)
            ->getQuery()
            ->getResult();

        if (count($tags) !== count($tagIds)) {
            throw new TagNotFoundException('One or more tags not found.');
        }

        return $tags;
    }
}
