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

            $data = $this->getFormData();

            $id = (int)$this-> resolveArg("id");

            // ユーザーが存在するか確認
            try {
                $user = $this->userRepository->findUserOfId($id);
            } catch (UserNotFoundException $e) {
                $this->logger->info("User with id `${id}` not found.");
                return $this->respondWithData('User not found', 404);
            }

            // Userを削除（論理削除）
            $this->userRepository->deleteUser($id);

            $this->logger->info("User of id `${id}` was deleted.");

            return $this->respondWithData(['message' => 'User deleted successfully']);
        }

        private function hasInvalidInput(array $data):bool
        {
            if (empty($data['id'])) {
                $this->logger->info("User deletion failed due to invalid input");
                return true;
            }
            return false;
        }
    }
?>