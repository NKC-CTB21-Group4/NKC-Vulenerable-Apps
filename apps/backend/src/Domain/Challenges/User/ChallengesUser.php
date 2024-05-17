<?php
declare(strict_types=1);

namespace App\Domain\Challenges\User;

use DateTime;
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
    private ?int $id = null;

    #[Column(type: 'string')]
    private string $username;

    #[Column(type: 'string',unique: true)]
    private string $email;

    #[Column(name: 'securePassword', type: 'string', length: 256)]
    private string $securePassword;

    #[Column(name: 'is_admin', type: 'boolean')]
    private bool $isAdmin;

    #[Column(name: 'registered_at', type: 'datetime', nullable: false)]
    private DateTime $registeredAt;

    #[Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt;


    public function __construct(string $username, string $email, string $password, bool $isAdmin)
    {
        $this->username = $username;
        $this->email = $email;
        $this->securePassword = password_hash($password,PASSWORD_DEFAULT);
        $this->isAdmin = $isAdmin;
        $this->registeredAt = new DateTime('now');
        $this->deletedAt = null;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->securePassword = password_hash($password,PASSWORD_DEFAULT);
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
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

    public function setDeletedAt():void
    {
        $this->deletedAt = new DateTime('now');
    }
    public function getDeletedAt():?DateTime
    {
        return $this->deletedAt;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'registered_at' => $this->registeredAt->format('Y-m-d H:i:s'),
        ];
    }
}
