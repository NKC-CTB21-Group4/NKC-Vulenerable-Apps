<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUser;

class UpdateChallengesUsersAction extends ChallengesUserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $data = $this->getFormData();

        // 必要なデータを検証
        if (empty($data['id']) || empty($data['username']) || empty($data['email']) || empty($data['password'])) {
          $this->logger->info("User Update failed");
          return $this->respondWithData('Invalid input', 400);
        }

        // 更新対象のユーザーを取得
        $user = $this->userRepository->findUserOfId((int)$data['id']);

        // ユーザーが存在しない場合はエラーを返す
        if ($user === null) {
            $this->logger->info("User not found");
            return $this->respondWithData('User not found', 404);
        }

        // ユーザーの情報を更新
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $this->userRepository->save($user);

        $this->logger->info("User updated successfully");

        return $this->respondWithData($user, 200);
    }
}
