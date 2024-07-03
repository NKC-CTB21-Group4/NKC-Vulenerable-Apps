<?php

declare(strict_types=1);

namespace App\Domain\Main\DirectMessage;

use App\Domain\Main\User\User;

interface DirectMessageRepository
{
  /**
   * @param User $sender
   * @param User $receiver
   * @return void
  */
  public function send(User $sender,User $receiver): void;

  /**
   * @param User $user
   * @return array
   */
  public function getDirectMessagePartners(User $user):array;

  /**
   * @param int $messageId
   * @return void
   * @throws DirectMessageNotFoundException
   */
  public function delete(int $messageId):void;
  
  /**
   * @param User $user1
   * @param User $user2
   * @return array
   */
   public function  getDirectMessages(User $user1,User $user2): array;

}
