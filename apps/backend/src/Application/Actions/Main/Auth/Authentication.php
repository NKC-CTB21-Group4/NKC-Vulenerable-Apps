<?php

declare(strict_types=1);

namespace App\Application\Actions\Main\Auth;

use App\Application\Actions\Action;
use App\Domain\Main\User\UserRepository;
use App\Domain\Main\Auth\AuthTokenRepository;
use App\Infrastructure\Persistence\Main\Auth\JwtService;
use Psr\Log\LoggerInterface;

abstract class Authentication extends Action {
    protected UserRepository $userRepository;
    protected JwtService $jwtService;
    protected AuthTokenRepository $jwtRepository;

    public function __construct(
      JwtService $jwtService,
      UserRepository $userRepository, 
      AuthTokenRepository $jwtRepository, 
      LoggerInterface $logger) {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->jwtRepository = $jwtRepository;
        $this->jwtService = $jwtService;
    }
}
