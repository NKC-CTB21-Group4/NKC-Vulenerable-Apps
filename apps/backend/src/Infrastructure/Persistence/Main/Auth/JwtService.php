<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Main\Auth;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \App\Infrastructure\Persistence\Main\Auth\DatabaseAuthTokenRepository;
use \App\Domain\Main\Auth\AuthToken;

class JwtService {
    protected $secretKey;
    protected $algorithm;
    private DatabaseAuthTokenRepository $authTokenRepository;

    public function __construct(DatabaseAuthTokenRepository $authTokenRepository) {
        $this->secretKey = 'secret-key';
        $this->algorithm = 'HS256';
        $this->authTokenRepository = $authTokenRepository;
    }

    // トークンを発行する
    public function generateToken(array $payload): string {
        $payload['iat'] = time();
        $payload['exp'] = time() + 86400; // トークン有効期限 (1日)
        $token = JWT::encode($payload, $this->secretKey, $this->algorithm);
        $this->authTokenRepository->save(new AuthToken($token,$payload['exp']));
        return $token;
    }

    // トークンを検証する
    public function validateToken(string $jwt): ?array {
        $tokenData = $this->authTokenRepository->findByToken($jwt);
        if (!$tokenData || $tokenData->getExpiresAt() < time() || $tokenData->isRevoked()) {
            return null;
        }

        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function revokeToken($jwt): ?bool{
        $tokenData = $this->authTokenRepository->findByToken($jwt);
        if (!$tokenData || $tokenData->getExpiresAt() < time() || $tokenData->isRevoked()) {
            return null;
        }
        $this->authTokenRepository->revokeToken($tokenData);
        return true;
    }
}
