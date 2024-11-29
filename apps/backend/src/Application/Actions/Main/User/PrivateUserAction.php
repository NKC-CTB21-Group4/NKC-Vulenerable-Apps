<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Main\User\User;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\UserPrivatedNotFoundException;

class PrivateUserAction extends UserAction
{
    public function action() : Response{
        $user = $this->getUserFromToken();

        $user = $this->checkUserAuthorization($user);
        if ($user === null) {
            return $this->respondWithData('Unauthorized', 403);
        }

        $id = $user->getId();
        if ($id === null) {
            $this->logger->info("User toggle failed");
            return $this->respondWithData('Invalid input', 400);
        }

        $this->userRepository->toggleIsPrivate($user);

        $this->logger->info("User toggle private successfully with ID: " . $user->getId());
        return $this->respondWithData(["message"=>"User toggle private successfully","user"=>$user]);

    }
}