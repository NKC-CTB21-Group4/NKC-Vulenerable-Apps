<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Auth;

use App\Application\Actions\Action;
use App\Domain\Challenges\User\ChallengesUserRepository;
use App\Domain\Challenges\Auth\ChallengesAuthTokenRepository;
use App\Infrastructure\Persistence\Challenges\Auth\ChallengesJwtService;
use Psr\Log\LoggerInterface;

abstract class ChallengesAuthentication extends Action {
    protected ChallengesUserRepository $userRepository;
    protected ChallengesJwtService $jwtService;
    protected ChallengesAuthTokenRepository $jwtRepository;

    public function __construct(ChallengesJwtService $jwtService,ChallengesUserRepository $userRepository, ChallengesAuthTokenRepository $jwtRepository, LoggerInterface $logger) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->jwtRepository = $jwtRepository;
        $this->jwtService = $jwtService;
    }
}
