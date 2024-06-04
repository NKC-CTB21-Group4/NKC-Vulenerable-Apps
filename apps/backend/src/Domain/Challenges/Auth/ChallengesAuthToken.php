<?php

declare(strict_types=1);

namespace App\Domain\Challenges\Auth;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use App\Domain\Challenges\User\ChallengesUser;

#[ORM\Entity, ORM\Table(name: 'challenges_auth_tokens')]
class ChallengesAuthToken
{

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id =null;


    #[ORM\Column(type: 'text')]
    private string $token;

    #[ORM\Column(name: 'expires_at', type: 'integer')]
    private int $expiresAt;

    #[ORM\Column(name: 'is_revoked', type: 'boolean', nullable: true)]
    private bool $isRevoked;

    public function __construct(string $token, int $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->isRevoked = false;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }

    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    public function revoke(): void
    {
        $this->isRevoked = true;
    }
}
