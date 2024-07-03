<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\DirectMessage;


use App\Domain\Main\DirectMessage\DirectMessage;
use App\Domain\Main\User\User;
use App\Domain\Main\DirectMessage\DirectMessageRepository;
use App\Domain\Main\DirectMessage\DirectMessageNotFoundException;
use App\Domain\Main\DirectMessage\DirectMessageCreationException;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\UserNotFoundException;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseDirectMessageRepository extends EntityRepository implements DirectMessageRepository
{
  private EntityManager $entityManager;
  private UserRepository $userRepository;

  public function __construct(EntityManager $entityManager,UserRepository $userRepository)
  {
    $this->entityManger = $entityManager;
    $this->userRepository = $userRepository;
    parent::__construct($entityManager, $entityManager->getClassMetadata(DirectMessage::class));
  }

  public function save(DirectMessage $directMessage):void
  {
    $this->_em->persist($directMessage);
    $this->_em->flush();
  }

  private function isDeleted(DirectMessage $directMessage):bool
  {
    return $directMessage->getDeletedAt() !== null;
  }

  public function send(User $sender,User $receiver,string $message): void
  {
    try{
      $directMessage = new DirectMessage($sender,$receiver,$message);
      save($directMessage);
    }catch(Exception $e){
      throw new DirectMessageCreationException();
    }
  }

  /**
   * @param User $user
   * @return array
   */
  public function getDirectMessagePartners(User $user):array
  {
    // Create a query builder to fetch all direct messages where the user is either the sender or receiver
    $qb = $this->createQueryBuilder('dm')
    ->where('dm.sender = :user')
    ->orWhere('dm.receiver = :user')
    ->setParameter('user', $user)
    ->orderBy('dm.sentAt', 'DESC');

    $directMessages = $qb->getQuery()->getResult();

    $partners = [];
    $seenPairs = [];

    foreach ($directMessages as $dm) {
        $senderId = $dm->getSenderId();
        $receiverId = $dm->getReceiverId();
        $pairKey = $senderId < $receiverId ? $senderId . '-' . $receiverId : $receiverId . '-' . $senderId;

        if (!isset($seenPairs[$pairKey])) {
            $partners[] = $dm;
            $seenPairs[$pairKey] = true;
        }
    }

    return $partners;
  }

  public function delete(int $messageId):void
  {
    $dm = $this->findDirectMessageOfId($messageId);
    $dm->setDeletedAt();
    $this->_em->flush();
  }

  public function getDirectMessages(User $user1, User $user2): array 
{
    return $this->createQueryBuilder('dm')
        ->where('dm.sender = :user1 AND dm.receiver = :user2')
        ->orWhere('dm.sender = :user2 AND dm.receiver = :user1')
        ->setParameter('user1', $user1)
        ->setParameter('user2', $user2)
        ->orderBy('dm.sentAt', 'ASC')
        ->getQuery()
        ->getResult();
}


  private function findDirectMessageOfId(int $id):DirectMessage 
  {
    $dm = parent::find((string) $id);

    if ($dm === null || $this->isDeleted($dm)){
      throw new DirectMessageNotFound();
    }

    return $dm;
  }
}
