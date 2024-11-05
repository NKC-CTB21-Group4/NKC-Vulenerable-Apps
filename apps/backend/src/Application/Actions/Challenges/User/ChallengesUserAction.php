<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\User;

use App\Application\Actions\Action;
use App\Domain\Challenges\User\ChallengesUserRepository;
use Psr\Log\LoggerInterface;

abstract class ChallengesUserAction extends Action
{
    protected ChallengesUserRepository $userRepository;

    public function __construct(LoggerInterface $logger, ChallengesUserRepository $userRepository)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
    }
}
