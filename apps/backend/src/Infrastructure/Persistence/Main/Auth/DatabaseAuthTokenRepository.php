<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Auth;

use App\Domain\Main\Auth\AuthToken;
use App\Domain\Main\Auth\AuthTokenRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class DatabaseAuthTokenRepository extends EntityRepository implements AuthTokenRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(AuthToken::class));
    }

    public function save(AuthToken $authToken): void
    {
        $this->entityManager->persist($authToken);
        $this->entityManager->flush();
    }

    public function findByToken(string $token): ?AuthToken
    {
        return $this->findOneBy(['token' => $token]);
    }

    public function revokeToken(AuthToken $authToken): void
    {
        $authToken->revoke();
        $this->entityManager->flush();
    }

    public function removeExpiredTokens(): void
    {
        $query = $this->entityManager->createQuery('DELETE FROM App\Domain\Main\Auth\AuthToken t WHERE t.expiresAt < :now');
        $query->setParameter('now', new \DateTime());
        $query->execute();
    }
}
