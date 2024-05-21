<?php
declare(strict_types=1);

namespace App\Domain\Challenges\News;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use App\Domain\Challenges\User\ChallengesUser;

#[ORM\Entity, ORM\Table(name: 'challenges_news')]
class ChallengesNews implements JsonSerializable
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ChallengesUser::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ChallengesUser $user;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'is_public', type: 'boolean')]
    private bool $isPublic;

    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt;

    public function __construct(ChallengesUser $user, string $title, string $content, bool $isPublic)
    {
        $this->user = $user;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = new DateTime('now');
        $this->isPublic = $isPublic;
        $this->deletedAt = null; // 初期値は削除されていない状態
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ChallengesUser
    {
        return $this->user;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    public function setDeletedAt(?DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->user->getUsername(),
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
