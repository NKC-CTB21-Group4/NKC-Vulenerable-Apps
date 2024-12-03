<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Follow;

use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Domain\Main\Follow\FollowRepository;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\User\User;
use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Http\Message\ResponseInterface as Response;

abstract class FollowAction extends Action
{
    protected FollowRepository $FollowRepository;
    protected UserRepository $userRepository;
    protected JwtService $jwtService;

    public function __construct(
        LoggerInterface $logger,
        FollowRepository $followRepository,
        UserRepository $userRepository,
        JwtService $jwtService
    ){
        parent::__construct($logger);
        $this->followRepository = $followRepository;
        $this->userRepository = $userRepository;
        $this->jwtService = $jwtService;
    }

    protected function getUserFromToken():?object
    {
        return (object)$this->request->getAttribute('token')['user'] ?? null;
    }

    protected function checkUserAuthorization(?object $user): ?User
    {
      if($user === null)return null;
        $userId = (int) $this->resolveArg('userId');
        $user = $this->userRepository->findUserOfId($user->id);
        if ($user->getId() !== $userId) {
            return null;
        }
        return $user;
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



