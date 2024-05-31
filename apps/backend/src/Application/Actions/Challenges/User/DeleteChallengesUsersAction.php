<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Domain\Challenges\User\ChallengesUserNotFoundException;

class DeleteChallengesUsersAction extends ChallengesUserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = $this->getFormData();

        // 必要なデータを検証
        if (empty($data['id'])) {
            $this->logger->info("User deletion failed");
            return $this->respondWithData('Invalid input', 400);
        }

        try {
            $this->userRepository->deleteUser($data['id']);
            $this->logger->info("User deleted successfully");
            return $this->respondWithData('User deleted successfully', 200);
        } catch (ChallengesUserNotFoundException $e) {
            $this->logger->error("User deletion failed: User not found");
            return $this->respondWithData('User not found', 404);
        } catch (\Exception $e) {
            $this->logger->error("User deletion failed: " . $e->getMessage());
            return $this->respondWithData('Failed to delete user', 500);
        }
    }
}
