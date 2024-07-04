<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\DirectMessage;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\UserNotFoundException;
use App\Domain\Main\DirectMessage\DirectMessageCreationException;
use App\Domain\Main\DirectMessage\DirectMessage;

class SendDirectMessageAction extends DirectMessageAction
{
  protected function action(): Response
  {
    $user = $this->getUserFromToken();
    
    $sender = $this->checkUserAuthorization($user,(int)$this->resolveArg('senderId'));
    if ($sender === null) {
      return $this->respondWithData('Unauthorized', 403);
    }

    $receiverId = (int)$this->resolveArg('receiverId');
    try{
      $receiver = $this->userRepository->findUserOfId($receiverId);
    }catch(UserNotFoundException $e){
      $this->logger->info("user with id `$receiverId` not found.");
      return $this->respondWithData("User Not Found.", 404);
    }

    //コンテンツ(文字)のチェック
    $data = $this->getFormData();

    $invalidResponse = $this->validateInputData($data);
    if($invalidResponse !== null){
      return $invalidResponse;
    }
    
    try{
      $directMessage = new DirectMessage($sender,$receiver,$data['message']);
      $this->directMessageRepository->send($directMessage);
    }catch(DirectMessageCreationException $e){
      return $this->respondWithData("Message could not be sent. Please check the user and try again.", 400);
    }
    return $this->respondWithData("Message sent successfully",200);
  }

  private function validateInputData(array $data): ?Response
  {
      if (empty($data['message'])) {
        $this->logger->info("DirectMessage creation failed due to invalid input");
        return $this->respondWithData('Invalid input', 400);
    }
    return null;
  }
}