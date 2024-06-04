<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Challenges\Auth;

use App\Domain\Challenges\Auth\ChallengesAuthToken;
use App\Domain\Challenges\Auth\ChallengesAuthTokenRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class DatabaseChallengesAuthTokenRepository extends EntityRepository implements ChallengesAuthTokenRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(ChallengesAuthToken::class));
    }

    public function save(ChallengesAuthToken $authToken): void
    {
        $this->entityManager->persist($authToken);
        $this->entityManager->flush();
    }

    public function findByToken(string $token): ?ChallengesAuthToken
    {
        return $this->findOneBy(['token' => $token]);
    }

    public function revokeToken(ChallengesAuthToken $authToken): void
    {
        $authToken->revoke();
        $this->entityManager->flush();
    }

    public function removeExpiredTokens(): void
    {
        $query = $this->entityManager->createQuery('DELETE FROM App\Domain\Challenges\Auth\ChallengesAuthToken t WHERE t.expiresAt < :now');
        $query->setParameter('now', new \DateTime());
        $query->execute();
    }
}
