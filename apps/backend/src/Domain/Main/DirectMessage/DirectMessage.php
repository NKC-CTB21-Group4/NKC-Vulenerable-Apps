<?php

declare(strict_types=1);

namespace App\Domain\Main\DirectMessage;

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

#[Entity,Table(name: 'direct_messages')]
class DirectMessage implements JsonSerializable
{
    #[Id, GeneratedValue, Column(type: 'integer')]
    private ?int $id = null;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'sender_id', referencedColumnName: 'id', nullable: false)]
    private User $sender;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'receiver_id', referencedColumnName: 'id', nullable: false)]
    private User $receiver;

    #[Column(type: 'text')]
    private string $message;

    #[Column(type: 'datetime')]
    private DateTime $sentAt;

    #[Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt = null;

    public function __construct(User $sender, User $receiver, string $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->message = $message;
        $this->sentAt = new DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getReceiver(): User
    {
        return $this->receiver;
    }

    public function getSenderId(): int 
    {
      return $this->sender->getId();
    }

    public function getReceiverId(): int 
    {
      return $this->receiver->getId();
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getSentAt(): DateTime
    {
        return $this->sentAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void 
    {
      $this->deletedAt = new DateTime('now');
    }

    public function isDeleted(): bool 
    {
      return $this->deletedAt ? true : false;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->getSenderId(),
            'receiver_id' => $this->getReceiverId(),
            'message' => $this->message,
            'sent_at' => $this->sentAt->format('Y-m-d H:i:s'),
        ];
    }
}
