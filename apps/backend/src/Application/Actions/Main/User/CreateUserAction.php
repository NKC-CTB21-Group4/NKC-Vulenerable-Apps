<?php
    declare(strict_types=1);

    namespace App\Application\Actions\Main\User;

    use Psr\Http\Message\ResponseInterface as Response;
    use App\Domain\Main\User\User;

    class CreateUserAction extends UserAction
    {
        protected function action(): Response
        {
            $data = $this->getFormData();

            // データを検証
            //必要なデータが一個でもない場合
            if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            $this->logger->info("User creation failed");
            return $this->respondWithData('Invalid input',400);
            }

            $user = new User($data['username'],$data['email'],$data['password'],false);
            $this->userRepository->createUser($user);
            
            $this->logger->info("User created successfully");

            return $this->respondWithData($user, 201);
        }
    }
?>