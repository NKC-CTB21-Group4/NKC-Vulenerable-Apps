<?php

declare(strict_types=1);

namespace App\Domain\Challenges\Auth;

use App\Domain\Challenges\Auth\ChallengesAuthToken;

interface ChallengesAuthTokenRepository
{
    public function save(ChallengesAuthToken $authToken): void;

    public function findByToken(string $token): ?ChallengesAuthToken;

    public function revokeToken(ChallengesAuthToken $authToken): void;

    public function removeExpiredTokens(): void;   
}
