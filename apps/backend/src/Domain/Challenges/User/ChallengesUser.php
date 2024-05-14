<?php
declare(strict_types=1);

namespace App\Domain\Challenges\User;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;

#[Entity, Table(name: 'challenges_users')]
final class ChallengesUser implements JsonSerializable
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id;

    #[Column(type: 'string', unique: true)]
    private string $username;

    #[Column(type: 'string',unique: true)]
    private string $email;

    #[Column(name: 'securePassword', type: 'string', length: 256)]
    private string $securePassword;

    #[Column(name: 'is_admin', type: 'boolean')]
    private bool $isAdmin;

    #[Column(name: "registered_at", type: "datetime", nullable: false)]
    private DateTimeImmutable $registeredAt;

    public function __construct(?int $id, string $username, string $email, string $password, bool $isAdmin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->securePassword = password_hash($password);
        $this->isAdmin = $isAdmin;
        $this->registeredAt = new DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSecurePassword(): string
    {
        return $this->securePassword;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'registered_at' => $this->registeredAt,
        ];
    }
}
