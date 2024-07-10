<?php 

declare(strict_types=1);

namespace App\Domain\Main\Report;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Main\User\User;
use App\Domain\Main\Post\Post;
use App\Domain\Main\Tag\Tag;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use JsonSerializable;

#[Entity, Table(name: 'reports')]
class Report implements JsonSerializable
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id = null;

    #[ManyToOne(targetEntity: Post::class)]
    #[JoinColumn(name: 'post_id', referencedColumnName: 'id', nullable: false)]
    private Post $post;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[Column(type: 'text', nullable: false)]
    private string $reason;

    #[Column(name: 'is_true',type: 'boolean',nullable:false)]
    private bool $isTrue;


    #[JoinTable(name: 'report_tags')]
    #[JoinColumn(name: 'report_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: Tag::class)]
    private Collection $tags;

    #[Column(type: 'datetime')]
    private DateTime $reportedAt;

    #[Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt;

    public function __construct(Post $post, User $user,array $tags, string $reason)
    {
        $this->post = $post;
        $this->user = $user;
        $this->reason = $reason;
        $this->isTrue = true;
        $this->reportedAt = new DateTime('now');
        $this->tags = new ArrayCollection();
        foreach ($tags as $tag) {
          $this->addTag($tag);
        }
        $this->deletedAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function isTrue(): bool
    {
        return $this->isTrue;
    }

    public function setIsTrue(bool $isTrue):void 
    {
      $this->isTrue = $isTrue;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getReportedAt(): DateTime
    {
        return $this->reportedAt;
    }

    public function isDeleted(): bool 
    {
        return $this->deletedAt == null ? false : true;
    }

    public function setIsDeletedAt():void 
    {
        $this->deletedAt = new DateTime('now');
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post->getId(),
            'user_id' => $this->user->getId(),
            'reason' => $this->reason,
            'is_true' => $this->isTrue,
            'tags' => $this->tags->toArray(),
            'reported_at' => $this->reportedAt->format('Y-m-d H:i:s'),
        ];
    }
}
