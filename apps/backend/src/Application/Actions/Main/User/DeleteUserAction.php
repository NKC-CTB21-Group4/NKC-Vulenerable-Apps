<?php
    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use Psr\Http\Message\ResponseInterface as Response;
    use App\Domain\Main\User\User;
    use App\Domain\Main\User\UserNotFoundException;

    class DeleteUserAction extends UserAction
    {
        public function action() : Response 
        {
            $user = $this->getUserFromToken();
    
            $user = $this->checkUserAuthorization($user);
            if ($user === null) {
                return $this->respondWithData('Unauthorized', 403);
            }

            $id = $user->getId();
            if ($id === null) {
                $this->logger->info("User deletion failed");
                return $this->respondWithData('Invalid input', 400);
            }

            // Userを削除（論理削除）
            try{
                $this->userRepository->deleteUser($id);
            }catch(UserNotFoundException $e) {
                $this->logger->info("User with id `${id}` not found.");
                return $this->respondWithData('User not found', 404);
            }

            $this->logger->info("User deleted successfully");
            return $this->respondWithData('User deleted successfully', 200);
        }
    }
?>