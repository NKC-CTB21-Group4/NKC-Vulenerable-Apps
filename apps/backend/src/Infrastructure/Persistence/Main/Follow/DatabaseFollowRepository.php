<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Follow;

use App\Domain\Main\Follow\FollowRepository;
use App\Domain\Main\User\User;
use App\Domain\Main\Follow\Follow;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use App\Domain\Main\Follow\FollowerNotFoundException;
use App\Domain\Main\Follow\FollowedNotFoundException;
use App\Domain\Main\Follow\FollowerCreateFailedException;
use App\Domain\Main\Follow\FollowerDeleteFailedException; 

class DatabaseFollowRepository extends EntityRepository implements FollowRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(Follow::class));
    }

    public function findOfFollower(int $followerId): array
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.follower = :followerId')
            ->setParameter('followerId', $followerId)
            ->getQuery();
    
        $results = $queryBuilder->getResult();

        if (empty($results)) {
            throw new FollowerNotFoundException();
        }

        return $results;
    }

    public function findOfFollowed(int $followedId): array
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.followed = :followedId')
            ->setParameter('followedId', $followedId)
            ->getQuery();

        $results = $queryBuilder->getResult();

        if (empty($results)) {
            throw new FollowedNotFoundException();
        }

        return $results;
    }

    public function addFollower(User $follower, User $followed): bool
    {
        try {
            $follow = new Follow($follower,$followed);

            $this->_em->persist($follow);
            $this->_em->flush();
            
            return true;
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            // 一意制約違反
            throw new FollowerCreateFailedException('Follower relationship already exists.', 0, $e);
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
            // 外部キー制約違反
            throw new FollowerCreateFailedException('Follower or Followed user does not exist.', 0, $e);
        } catch (\Doctrine\ORM\ORMException $e) {
            // その他のORMエラー
            throw new FollowerCreateFailedException('An ORM error occurred.', 0, $e);
        } catch (\Exception $e) {
            // その他の一般的なエラー
            throw new FollowerCreateFailedException('An unexpected error occurred.', 0, $e);
        }
    }

    public function deleteFollower(int $followerId, int $followedId): void
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.follower = :followerId')
            ->andWhere('f.followed = :followedId')
            ->setParameter('followerId', $followerId)
            ->setParameter('followedId', $followedId)
            ->getQuery();

        $follow = $queryBuilder->getOneOrNullResult();

        if ($follow === null) {
            throw new FollowerNotFoundException();
        }

        try {
            $this->_em->remove($follow);
            $this->_em->flush();
        } catch (\Doctrine\ORM\ORMException $e) {
            // その他のORMエラー
            throw new FollowerDeleteFailedException('An ORM error occurred while deleting the follower relationship.', 0, $e);
        } catch (\Exception $e) {
            // その他の一般的なエラー
            throw new FollowerDeleteFailedException('An unexpected error occurred while deleting the follower relationship.', 0, $e);
        }
    }
}
