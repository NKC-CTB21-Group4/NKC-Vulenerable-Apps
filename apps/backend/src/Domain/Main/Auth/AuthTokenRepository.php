<?php

declare(strict_types=1);

namespace App\Domain\Main\Auth;

use App\Domain\Main\Auth\AuthToken;

interface AuthTokenRepository
{
    public function save(AuthToken $authToken): void;

    public function findByToken(string $token): ?AuthToken;

    public function revokeToken(AuthToken $authToken): void;

    public function removeExpiredTokens(): void;   
}
