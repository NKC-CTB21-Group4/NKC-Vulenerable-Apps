<?php

declare(strict_types=1);

namespace App\Application\Actions\Challenges\Diary;

use App\Application\Actions\Action;
use App\Domain\Challenges\Diary\ChallengesDiaryRepository;
use App\Domain\Challenges\User\ChallengesUserRepository;
use Psr\Log\LoggerInterface;

abstract class ChallengesDiaryAction extends Action
{
    protected ChallengesDiaryRepository $diaryRepository;
    protected ChallengesUserRepository $userRepository;

    public function __construct(
        LoggerInterface $logger,
        ChallengesDiaryRepository $diaryRepository,
        ChallengesUserRepository $userRepository
    ) {
        parent::__construct($logger);
        $this->diaryRepository = $diaryRepository;
        $this->userRepository = $userRepository;
    }
}
