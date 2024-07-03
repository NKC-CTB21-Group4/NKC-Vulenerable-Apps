<?php

declare(strict_types=1);

namespace App\Domain\Main\Follower;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use App\Domain\Main\User\User;

#[Entity, Table(name: 'followers')]
class Follower implements JsonSerializable
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'follower_id', referencedColumnName: 'id', nullable:false)]
    private User $follower;
    
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'followed_id', referencedColumnName: 'id', nullable:false)]
    private User $followed;

    #[Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    public function __construct(User $follower, User $followed)
    {
        $this->follower = $follower;
        $this->followed = $followed;
        $this->createdAt = new DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollower(): User
    {
        return $this->follower;
    }

    public function getFollowed(): User
    {
        return $this->followed;
    }

    public function getFollowerId(): int 
    {
        return $this->follower->getId();
    }

    public function getFollowdId(): int 
    {
        return $this->followed->getId();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'follower_id' => $this->follower->getId(),
            'followed_id' => $this->followed->getId(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
?>
