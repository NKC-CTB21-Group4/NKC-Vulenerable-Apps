<?php
    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use Psr\Http\Message\ResponseInterface as Response;
    use App\Domain\Main\User\User;

    class UpdateUserAction extends UserAction
    {
        protected function action(): Response
        {
            $data = $this->getFormData();

            $user = $this->getUserFromToken();
    
            $user = $this->checkUserAuthorization($user);
            if ($user === null) {
                return $this->respondWithData('Unauthorized', 403);
            }

            $id = $user->getId();
            if ($id === null) {
                $this->logger->info("User update failed");
                return $this->respondWithData('Invalid input', 400);
            }
            // データを検証
            //必要なデータが一個もない場合
            if (empty($data['username']) && empty($data['email']) && empty($data['password']) && empty($data['profile'])) {
              $this->logger->info("User update failed");
              return $this->respondWithData('Invalid input',400);
            }

            try{
              $user = $this->userRepository->updateUser($id,$data);
            }catch(UserNotFoundException $e) {
                $this->logger->info("Update failed");
                return $this->respondWithData('Update failed', 404);
            }
            $this->logger->info("User update successfully");

            return $this->respondWithData($user, 200);
        }
    }
?>