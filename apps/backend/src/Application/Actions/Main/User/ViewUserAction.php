<?php
    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use Psr\Http\Message\ResponseInterface as Response;
    use App\Domain\Main\User\UserNotFoundException;
    
    class ViewUserAction extends UserAction
    {
        protected function action(): Response
        {
            $id = (int)$this-> resolveArg("id");

            try {
                //例外を返す可能性がある
                $user = $this->userRepository->findUserOfId($id);
            } catch (UserNotFoundException $e) {
                $this->logger->info("user with id `$id` not found.");
                return $this->respondWithData("User Not Found.", 404);
            }

            //ユーザが見つかったらユーザの情報を
            $this->logger->info("user of `$id` was viewed.");
            return $this->respondWithData($user);
        }
    }
?>