<?php

declare(strict_types=1);

namespace App\Domain\Main\Post;

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

#[Entity, Table(name: 'posts')]
class Post implements JsonSerializable
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable:false)]
    private User $author;

    #[Column(type: 'text',nullable:false)]
    private string $content;

    #[Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt;

    public function __construct(User $author, string $content)
    {
        $this->author = $author;
        $this->content = $content;
        $this->createdAt = new DateTime('now');
        $this->deletedAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new DateTime('now');
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->author->getId(),
            'author_name' =>$this->author->getUsername(),
            'content' => $this->content,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }
}
