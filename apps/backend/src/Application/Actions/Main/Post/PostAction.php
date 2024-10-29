<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Post;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Post\PostRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\User;
use App\Domain\Main\Follow\FollowRepository;
use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Http\Message\ResponseInterface as Response;

abstract class PostAction extends Action
{
    protected PostRepository $postRepository;
    protected UserRepository $userRepository;
    protected FollowRepository $followRepository;
    protected JwtService $jwtService;

    public function __construct(
        LoggerInterface $logger,
        PostRepository $postRepository,
        UserRepository $userRepository,
        FollowRepository $followRepository,
        JwtService $jwtService
    ){
        parent::__construct($logger);
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->followRepository = $followRepository;
        $this->jwtService = $jwtService;
    }

    protected function getUserFromToken(): ?object
    {
        return (object)$this->request->getAttribute('token')['user'] ?? null;
    }

    protected function checkUserAuthorization(?object $user): ?User
    {
        if ($user === null) return null;
        $userId = (int) $user->id;
        $authenticatedUser = $this->userRepository->findUserOfId($userId);
        return $authenticatedUser;
    }

    protected function getUserFromHeader(): ?User
    {
        // Authorization ヘッダーを取得
        $authHeader = $this->request->getHeader('Authorization');

        // ヘッダーが存在しない、または空の場合は null を返す
        if (empty($authHeader[0])) {
            return null;
        }

        // ヘッダーが Bearer トークン形式か確認
        if (!preg_match('/Bearer\s(\S+)/', $authHeader[0], $matches)) {
            return null;
        }

        $token = $matches[1];

        if (!$token) {
            return null;
        }

        // トークンを検証
        try {
            $decoded = $this->jwtService->validateToken($token);
            if (!isset($decoded["user"])) {
                return null; // user が存在しない場合
            }
            $userData = $decoded["user"]; // stdClass の場合
            $userId = $userData->id; // stdClass の場合
            return $this->userRepository->findUserOfId((int)$userId);
        } catch (\Exception $e) {
            // トークンの検証に失敗した場合、null を返す
            return null;
        }
    }
}
