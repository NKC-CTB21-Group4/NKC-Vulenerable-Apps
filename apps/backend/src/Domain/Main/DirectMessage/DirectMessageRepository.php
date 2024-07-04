<?php

declare(strict_types=1);

namespace App\Domain\Main\DirectMessage;

use App\Domain\Main\User\User;
use App\Domain\Main\DirectMessage\DirectMessage;

interface DirectMessageRepository
{
  /**
   * @param DirectMessage $directMessage
   * @return void
   * @throws DirectMessageCreationException
  */
  public function send(DirectMessage $directMessage): void;

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
