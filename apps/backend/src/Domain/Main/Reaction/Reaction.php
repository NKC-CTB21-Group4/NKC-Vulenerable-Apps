<?php

declare(strict_types=1);

namespace App\Domain\Main\Reaction;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\Post;

#[Entity, Table(name: 'reactions')]
class Reaction implements JsonSerializable
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id',nullable:false)]
    private User $user;

    #[ManyToOne(targetEntity: Post::class)]
    #[JoinColumn(name: 'post_id', referencedColumnName: 'id',nullable:false)]
    private Post $post;

    #[Column(name: 'is_fav', type: 'boolean',nullable:false)]
    private bool $isFav;

    public function __construct(User $user, Post $post, bool $isFav)
    {
        $this->user = $user;
        $this->post = $post;
        $this->isFav = $isFav;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }
    public function getUserId():int
    {
        return $this->user->getId();
    }

    public function getPost(): Post
    {
        return $this->post;
    }
    public function getPostId(): int
    {
        return $this->post->getId();
    }

    public function isFav(): bool
    {
        return $this->isFav;
    }
    public function toggleFav():bool
    {
        $this->isFav = !$this->isFav;
        return $this->isFav;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->getId(),
            'post_id' => $this->post->getId(),
            'is_fav' => $this->isFav,
        ];
    }
}
