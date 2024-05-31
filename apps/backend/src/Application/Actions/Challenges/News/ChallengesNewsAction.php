<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\News;

use App\Application\Actions\Action;
use App\Domain\Challenges\News\ChallengesNewsRepository;
use App\Domain\Challenges\User\ChallengesUserRepository;
use Psr\Log\LoggerInterface;

abstract class ChallengesNewsAction extends Action
{
    protected ChallengesNewsRepository $newsRepository;
    protected ChallengesUserRepository $userRepository;

    public function __construct(
        LoggerInterface $logger,
        ChallengesNewsRepository $newsRepository,
        ChallengesUserRepository $userRepository
    ) {
        parent::__construct($logger);
        $this->newsRepository = $newsRepository;
        $this->userRepository = $userRepository;
    }
}
