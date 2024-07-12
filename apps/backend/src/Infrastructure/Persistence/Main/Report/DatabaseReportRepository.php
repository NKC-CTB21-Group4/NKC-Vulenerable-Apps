<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Report;

use App\Domain\Main\Report\Report;
use App\Domain\Main\Report\ReportRepository;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\Post;
use App\Domain\Main\Tag\Tag;
use App\Domain\Main\Report\ReportNotFoundException;
use App\Domain\Main\Report\ReportCreationFailedException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseReportRepository extends EntityRepository implements ReportRepository
{
  private EntityManager $entityManager;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
    parent::__construct($entityManager, $entityManager->getClassMetadata(Report::class));
  }

  public function save(Report $report): void
  {
    $this->_em->persist($report);
    $this->_em->flush();
  }

  /**
   * @param Report $report
   * @return Report
   * @throws ReportCreationFailedException
   */
  public function create(Report $report): Report
  {
    try {
      $this->_em->persist($report);
      $this->_em->flush();
      return $report;
    } catch (\Exception $e) {
      throw new ReportCreationFailedException();
    }
  }

  /**
   * @return Report[]
   */
  public function findAll(): array
  {
    return array_filter(parent::findAll(), function ($report) {
      return !$report->isDeleted();
    });
  }

  /**
   * @param int $reportId
   * @return Report
   * @throws ReportNotFoundException
   */
  public function findByReportId(int $reportId): Report
  {
    $report = parent::find($reportId);

    if ($report === null || $report->isDeleted()) {
      throw new ReportNotFoundException();
    }

    return $report;
  }

  /**
   * @param Post $post
   * @return Report[]
   */
  public function findByPost(Post $post): array
  {
    $queryBuilder = $this->createQueryBuilder('r')
      ->andWhere('r.post = :post')
      ->andWhere('r.deletedAt IS NULL')
      ->setParameter('post', $post)
      ->getQuery();

    return $queryBuilder->getResult();
  }

  /**
   * @return Report[]
   */
  public function getReportedPosts(): array
  {
    $queryBuilder = $this->createQueryBuilder('r')
    ->select('IDENTITY(r.post) as postId, COUNT(r.id) as reportCount')
    ->where('r.deletedAt IS NULL')
    ->groupBy('r.post')
    ->getQuery();
    return $queryBuilder->getResult();
  }

  /**
   * @param Tag $tag
   * @return void
   */
  public function addTag(Tag $tag): void
  {
    $this->tags->add($tag);
  }

  /**
   * @param Tag $tag
   * @return void
   */
  public function removeTag(Tag $tag): void
  {
    $this->tags->removeElement($tag);
  }

  /**
   * @param int $tagId
   * @return Report[]
   */
  public function findReportsWithTags(array $tagIds): array
  {
    $queryBuilder = $this->createQueryBuilder('r')
      ->join('r.tags', 't')
      ->andWhere('t.id IN (:tagIds)')
      ->andWhere('r.deletedAt IS NULL')
      ->setParameter('tagIds', $tagIds)
      ->groupBy('r.id')
      ->having('COUNT(t.id) = :tagCount')
      ->setParameter('tagCount', count($tagIds))
      ->getQuery();

    return $queryBuilder->getResult();
  }
}
